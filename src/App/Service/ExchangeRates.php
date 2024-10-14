<?php declare(strict_types=1);

namespace App\Service;

use App\Currency;
use DateTime;
use Throwable;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ExchangeRates extends AbstractHttpService implements ExchangeRatesServiceInterface
{
    protected $currencies = array();

    public function __construct(string $baseUri, HttpClientInterface $httpClient, array $currencies)
    {
        $this->setBaseUri($baseUri);
        $this->setHttpClient($httpClient);
        $this->loadCurrencies($currencies);
    }

    private function loadCurrencies(array $currencies): void
    {
        foreach ($currencies as $code => $config) {
            $code = strtoupper($code);
            $this->currencies[$code] = Currency::create($code,
                $config['bid'] ?? null,
                $config['ask'] ?? null);
        }
    }

    public function getCurrency(string $currencyCode) : Currency
    {
        return $this->currencies[$currencyCode];
    }

    public function ifWeSell(string $currencyCode): bool
    {
        if (!$this->currencies[$currencyCode])
            return false;

        return $this->getCurrency($currencyCode)->isAskOffered();
    }

    public function ifWeBuy(string $currencyCode): bool
    {
        if (!$this->currencies[$currencyCode])
            return false;

        return $this->getCurrency($currencyCode)->isBidOffered();
    }

    public function isCurrencyActive(string $currencyCode): bool
    {
        return !empty($this->currencies[$currencyCode]);
    }

    /**
     * @param DateTime $date
     * @param array $options
     * @return array
     *
     * @throws ClientExceptionInterface
     * @throws Throwable
     * @throws TransportExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getRatesForDate(DateTime $date, array $options = array()): array
    {
        try {
            return $this->prepareJson($this->call('GET',
                $date->format('Y-m-d').'?format=json')
                    ->getContent());
        } catch (ClientException  $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                $this->getLogger()->warning('Upstream server responded with 404');
                throw new ExchangeRates404();
            } else {
                $this->getLogger()->error($e);
                throw $e;
            }
        } catch (Throwable $e) {
            $this->getLogger()->alert($e);
            throw $e;
        }
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Throwable
     */
    protected function call(string $method, string $url, ?array $options = []): ResponseInterface
    {
        return $this->getHttpClient()
            ->request('GET', $this->getBaseUri() . $url, $options);
    }

    protected function prepareJson(string $json) : array
    {
        $json = json_decode($json);
        if (!$json)
            throw new \RuntimeException('Invalid json');

        $data = array();
        foreach ($json[0]->rates as $rate) {
            if (!$this->isCurrencyActive($rate->code))
                continue;

            $item = new \stdClass();
            $item->code = $rate->code;
            $item->name = $rate->currency;

            if ($this->ifWeBuy($rate->code))
                $item->bid = $this->getCurrency($rate->code)->getBidPrice($rate->mid);

            if ($this->ifWeSell($rate->code))
                $item->ask = $this->getCurrency($rate->code)->getAskPrice($rate->mid);
            $data[] = $item;
        }
        $this->sortByName($data);
        return $data;
    }

    protected function sortByName(array &$data) : void
    {
        usort($data, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });
    }
}
