<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

trait HttpServiceTrait
{
    use ServiceTrait;

    protected $baseUri;
    protected $httpClient;

    public function setBaseUri(string $baseUri) : void
    {
        $this->baseUri = $baseUri;
    }

    public function getBaseUri() : string
    {
        return $this->baseUri;
    }

    public function setHttpClient(HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    public function getHttpClient() : HttpClientInterface
    {
        return $this->httpClient;
    }
}
