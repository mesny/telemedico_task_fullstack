<?php declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerAwareInterface;

abstract class AbstractHttpService implements LoggerAwareInterface
{
    use HttpServiceTrait;

    abstract protected function call(string $method, string $url, array $options = []);

}
