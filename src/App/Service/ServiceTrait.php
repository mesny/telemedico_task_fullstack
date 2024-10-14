<?php declare(strict_types=1);

namespace App\Service;


use Psr\Log\LoggerInterface;

trait ServiceTrait
{
    protected $serviceName;
    protected $logger;

    public function setServiceName($name) : void
    {
        $this->serviceName = $name;
    }

    public function getServiceName() : string
    {
        return $this->serviceName;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function getLogger() : LoggerInterface
    {
        return $this->logger;
    }
}
