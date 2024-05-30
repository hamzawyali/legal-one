<?php

namespace App\DTO;

class LogFileDTO
{
    /**
     * @param string $dateTime
     * @param string $serviceName
     * @param int $statusCode
     */
    public function __construct(
        public string $dateTime,
        public string $serviceName,
        public int    $statusCode
    )
    {
    }

    /**
     * @param $line
     * @return LogFileDTO
     */
    public static function fromLogLine($line): LogFileDTO
    {
        preg_match('/^(\S+) - - \[(.*?)\] "(.*?)" (\d+)$/', $line, $matches);

        $dateTime = \DateTime::createFromFormat('d/M/Y:H:i:s O', $matches[2]);
        $date = $dateTime->format('Y-m-d H:i:s');

        return new self(
            $date,
            $matches[1],
            $matches[4]
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'dateTime' => $this->dateTime,
            'serviceName' => $this->serviceName,
            'statusCode' => $this->statusCode,
        ];
    }
}