<?php declare(strict_types=1);

namespace App\Logger;

use Psr\Log\LoggerInterface;

class StderrLogger implements LoggerInterface
{

    public function emergency($message, array $context = array())
    {
        fprintf(STDERR, "%s [EMERGENCY] %s\n%s\n",
            date('c'), $message, var_export($context, true));
    }

    public function alert($message, array $context = array())
    {
        fprintf(STDERR, "%s [ALERT] %s\n%s\n",
            date('c'), $message, var_export($context, true));
    }

    public function critical($message, array $context = array())
    {
        fprintf(STDERR, "%s [CRITICAL] %s\n%s\n",
            date('c'), $message, var_export($context, true));
    }

    public function error($message, array $context = array())
    {
        fprintf(STDERR, "%s [ERROR] %s\n%s\n",
            date('c'), $message, var_export($context, true));
    }

    public function warning($message, array $context = array())
    {
        fprintf(STDERR, "%s [ERROR] %s\n%s\n",
            date('c'), $message, var_export($context, true));
    }

    public function notice($message, array $context = array())
    {
        fprintf(STDERR, "%s [ERROR] %s\n%s\n",
            date('c'), $message, var_export($context, true));
    }

    public function info($message, array $context = array())
    {
        fprintf(STDERR, "%s [ERROR] %s\n%s\n",
            date('c'), $message, var_export($context, true));
    }

    public function debug($message, array $context = array())
    {
        fprintf(STDERR, "%s [ERROR] %s\n%s\n",
            date('c'), $message, var_export($context, true));
    }

    public function log($level, $message, array $context = array())
    {
        fprintf(STDERR, "%s [ERROR] %s\n%s\n",
            date('c'), $message, var_export($context, true));
    }
}
