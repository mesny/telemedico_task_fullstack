<?php declare(strict_types=1);

namespace Unit\StreamLogger;

use App\Logger\StreamLogger;
use PHPUnit\Framework\TestCase;

use function stream_get_contents;

class StreamLoggerTest extends TestCase
{
    private const DATA_EMERGENCY = 'emergency';
    const DATA_CRITICAL = 'critical';
    const DATA_ALERT = 'alert';
    const DATA_ERROR = 'error';
    const DATA_WARNING = 'warning';
    const DATA_NOTICE = 'notice';
    const DATA_INFO = 'info';
    const DATA_DEBUG = 'debug';

    private const DATA_MAX_LENGTH = 512;

    protected $stream = null;

    public function setUp(): void
    {
        $this->stream = fopen('php://memory', 'w+');
    }

    public function tearDown(): void
    {
        fclose($this->stream);
    }

    public function testEmergency(): void
    {
        $logger = new StreamLogger($this->stream);
        $date = $level = $message = '';

        // emergency
        $logger->emergency(StreamLogger::LEVEL_EMERGENCY);
        $this->readLogFromStream($date, $level, $message);

        $dateObj = date_create_from_format('c', $date);
        $this->assertInstanceOf(\DateTime::class, $dateObj);

        $this->assertEquals('[' . StreamLogger::LEVEL_EMERGENCY . ']', $level);
        $this->assertEquals(self::DATA_EMERGENCY, $message);
    }

    public function testAlert(): void
    {
        $logger = new StreamLogger($this->stream);
        $date = $level = $message = '';

        // alert
        $logger->alert(StreamLogger::LEVEL_ALERT);
        $this->readLogFromStream($date, $level, $message);

        $this->assertEquals('[' . StreamLogger::LEVEL_ALERT . ']', $level);
        $this->assertEquals(StreamLogger::LEVEL_ALERT, $message);

    }

    public function testCritical(): void
    {
        $logger = new StreamLogger($this->stream);
        $date = $level = $message = '';

        // critical
        $logger->critical(StreamLogger::LEVEL_CRITICAL);
        $this->readLogFromStream($date, $level, $message);

        $this->assertEquals('[' . StreamLogger::LEVEL_CRITICAL . ']', $level);
        $this->assertEquals(self::DATA_CRITICAL, $message);

    }
    public function testError(): void
    {
        $logger = new StreamLogger($this->stream);
        $date = $level = $message = '';

        // error
        $logger->error(StreamLogger::LEVEL_ERROR);
        $this->readLogFromStream($date, $level, $message);

        $this->assertEquals('['.StreamLogger::LEVEL_ERROR.']', $level);
        $this->assertEquals(self::DATA_ERROR, $message);
    }

    // warning
    // notice
    // info
    // debug
    // ...

    private function readLogFromStream(&$date, &$level, &$message)
    {
        $date = $level = $message = '';

        $data = fgets($this->stream);
        [$date, $level, $message] = explode(' ', $data);
    }

}
