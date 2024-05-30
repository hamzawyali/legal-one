<?php

namespace App\Tests\Message;

use App\Message\LogFileMessage;
use PHPUnit\Framework\TestCase;

class LogFileMessageTest extends TestCase
{
    public function testGetLogFilePath()
    {
        $message = new LogFileMessage('logs.log');
        $this->assertEquals('logs.log', $message->getLogFilePath());
    }
}