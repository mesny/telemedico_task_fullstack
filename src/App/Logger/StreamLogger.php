<?php declare(strict_types=1);

namespace App\Logger;

use Psr\Log\LoggerInterface;

class StreamLogger implements LoggerInterface
{
    public const LEVEL_EMERGENCY = 'emergency';
    public const LEVEL_ALERT = 'alert';
    public const LEVEL_CRITICAL = 'critical';
    public const LEVEL_ERROR = 'error';
    public const LEVEL_WARNING = 'warning';
    public const LEVEL_NOTICE = 'notice';
    public const LEVEL_INFO = 'info';
    public const LEVEL_DEBUG = 'debug';


    private $stream;
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('Stream must be a resource');
        }
        $this->stream = $stream;
    }

    public function emergency($message, array $context = array())
    {
        $this->log(self::LEVEL_EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->log(self::LEVEL_ALERT, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        $this->log(self::LEVEL_CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->log(self::LEVEL_WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->log(self::LEVEL_NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->log(self::LEVEL_INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $content = trim(sprintf("%s [%s] %s\n%s\n",
            date('c'), $level, $message,
            !empty($context) ? var_export($context, true) : ''));

        fputs($this->stream, $content.PHP_EOL);
        fflush($this->stream);
    }
}
