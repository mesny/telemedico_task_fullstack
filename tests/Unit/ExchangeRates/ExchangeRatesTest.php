<?php

namespace Unit\CurrencyRates;

use PHPUnit\Framework\TestCase;

use App\Service\ExchangeRates;

use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ExchangeRatesTest extends TestCase
{

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface|
     * @throws \Throwable
     */
    public function testApiCall()
    {
        $validResponse = $this->getDefaultResponseContent();
        $respFactory = function () use ($validResponse) {
            return new MockResponse($validResponse);
        };

        $baseUri = 'https://dummy/';
        $client = new MockHttpClient($respFactory, $baseUri);
        $service = new ExchangeRates($baseUri, $client, array());

        $response = $service->call('GET', $baseUri.'?format=json');
        $jsonContent = $response->getContent();

        $this->assertJsonStringEqualsJsonString($validResponse, $jsonContent);
    }

    public function getDefaultResponseContent() : string
    {
        return <<<EOD
        [
  {
    "table": "A",
    "no": "199/A/NBP/2024",
    "effectiveDate": "2024-10-11",
    "rates": [
      {
        "currency": "bat (Tajlandia)",
        "code": "THB",
        "mid": 0.1177
      },
      {
        "currency": "dolar amerykański",
        "code": "USD",
        "mid": 3.9204
      },
      {
        "currency": "dolar australijski",
        "code": "AUD",
        "mid": 2.6418
      },
      {
        "currency": "dolar Hongkongu",
        "code": "HKD",
        "mid": 0.5045
      },
      {
        "currency": "dolar kanadyjski",
        "code": "CAD",
        "mid": 2.8483
      },
      {
        "currency": "dolar nowozelandzki",
        "code": "NZD",
        "mid": 2.3906
      },
      {
        "currency": "dolar singapurski",
        "code": "SGD",
        "mid": 3.0034
      },
      {
        "currency": "euro",
        "code": "EUR",
        "mid": 4.2926
      },
      {
        "currency": "forint (Węgry)",
        "code": "HUF",
        "mid": 0.010706
      },
      {
        "currency": "frank szwajcarski",
        "code": "CHF",
        "mid": 4.579
      },
      {
        "currency": "funt szterling",
        "code": "GBP",
        "mid": 5.1252
      },
      {
        "currency": "hrywna (Ukraina)",
        "code": "UAH",
        "mid": 0.0951
      },
      {
        "currency": "jen (Japonia)",
        "code": "JPY",
        "mid": 0.026356
      },
      {
        "currency": "korona czeska",
        "code": "CZK",
        "mid": 0.1695
      },
      {
        "currency": "korona duńska",
        "code": "DKK",
        "mid": 0.5754
      },
      {
        "currency": "korona islandzka",
        "code": "ISK",
        "mid": 0.028868
      },
      {
        "currency": "korona norweska",
        "code": "NOK",
        "mid": 0.3653
      },
      {
        "currency": "korona szwedzka",
        "code": "SEK",
        "mid": 0.3776
      },
      {
        "currency": "lej rumuński",
        "code": "RON",
        "mid": 0.8627
      },
      {
        "currency": "lew (Bułgaria)",
        "code": "BGN",
        "mid": 2.1947
      },
      {
        "currency": "lira turecka",
        "code": "TRY",
        "mid": 0.1143
      },
      {
        "currency": "nowy izraelski szekel",
        "code": "ILS",
        "mid": 1.0417
      },
      {
        "currency": "peso chilijskie",
        "code": "CLP",
        "mid": 0.004209
      },
      {
        "currency": "peso filipińskie",
        "code": "PHP",
        "mid": 0.0686
      },
      {
        "currency": "peso meksykańskie",
        "code": "MXN",
        "mid": 0.2018
      },
      {
        "currency": "rand (Republika Południowej Afryki)",
        "code": "ZAR",
        "mid": 0.2246
      },
      {
        "currency": "real (Brazylia)",
        "code": "BRL",
        "mid": 0.7022
      },
      {
        "currency": "ringgit (Malezja)",
        "code": "MYR",
        "mid": 0.9152
      },
      {
        "currency": "rupia indonezyjska",
        "code": "IDR",
        "mid": 0.00025167
      },
      {
        "currency": "rupia indyjska",
        "code": "INR",
        "mid": 0.046636
      },
      {
        "currency": "won południowokoreański",
        "code": "KRW",
        "mid": 0.002906
      },
      {
        "currency": "yuan renminbi (Chiny)",
        "code": "CNY",
        "mid": 0.5545
      },
      {
        "currency": "SDR (MFW)",
        "code": "XDR",
        "mid": 5.2553
      }
    ]
  }
]
EOD;
    }



}