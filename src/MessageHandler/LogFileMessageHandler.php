<?php

namespace App\MessageHandler;

use App\Message\LogFileMessage;
use App\Service\LogFileService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class LogFileMessageHandler
{
    /**
     * @param LogFileService $logFileService
     */
    public function __construct(private LogFileService $logFileService)
    {
    }

    /**
     * @param LogFileMessage $message
     * @return void
     */
    public function __invoke(LogFileMessage $message): void
    {
        $logFilePath = $message->getLogFilePath();
        $this->logFileService->processLogFile($logFilePath);
    }
}