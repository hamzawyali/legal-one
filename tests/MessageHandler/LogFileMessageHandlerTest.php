<?php

namespace App\Tests\MessageHandler;

use App\Message\LogFileMessage;
use App\MessageHandler\LogFileMessageHandler;
use App\Service\LogFileService;
use PHPUnit\Framework\TestCase;

class LogFileMessageHandlerTest extends TestCase
{
    public function testInvoke()
    {
        $logFileService = $this->createMock(LogFileService::class);
        $logFileService->expects($this->once())
            ->method('processLogFile')
            ->with('logs.log');

        $handler = new LogFileMessageHandler($logFileService);
        $message = new LogFileMessage('logs.log');

        $handler($message);
    }
}
