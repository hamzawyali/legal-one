<?php

namespace App\Message;

class LogFileMessage
{
    /**
     * @param string $logFilePath
     */
    public function __construct(private string $logFilePath)
    {
    }

    /**
     * @return string
     */
    public function getLogFilePath(): string
    {
        return $this->logFilePath;
    }
}