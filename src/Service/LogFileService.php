<?php

namespace App\Service;

use App\DTO\LogFileDTO;
use App\Entity\LogFilePosition;
use App\Repository\LogFilePositionRepository;
use App\Repository\LogFileRepository;
use App\Repository\LogPositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class LogFileService
{
    /**
     * @param LogFileRepository $logFileRepository
     * @param LogFilePositionRepository $logFilePositionRepository
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     */
    public function __construct(
        private LogFileRepository         $logFileRepository,
        private LogFilePositionRepository $logFilePositionRepository,
        private EntityManagerInterface    $em,
        private LoggerInterface           $logger
    )
    {
    }

    /**
     * @param string $filePath
     * @return void
     */
    public function processLogFile(string $filePath): void
    {
        ini_set('memory_limit', '2G');

        $logFilePosition = $this->logFilePositionRepository->findOneBy(['filePath' => $filePath]);
        if (!$logFilePosition) {
            $logFilePosition = new LogFilePosition;
            $logFilePosition->setFilePath($filePath);
            $logFilePosition->setLineNumber(0);
        }

        $handle = fopen($filePath, 'r');

        if ($handle) {
            $currentLineNumber = 0;
            $batchSize = 1000;
            $logs = [];

            while (($line = fgets($handle)) !== false) {
                $currentLineNumber++;

                if ($currentLineNumber <= $logFilePosition->getLineNumber()) {
                    continue; // Skip already processed lines
                }

                $logData = LogFileDTO::fromLogLine($line);
                if ($logData) {
                    $logs[] = $logData->toArray();
                }

                if (count($logs) >= $batchSize) {
                    $this->persistLogs($logs, $currentLineNumber, $logFilePosition);
                    $logs = [];
                }
            }

            if (!empty($logs)) {
                $this->persistLogs($logs, $currentLineNumber, $logFilePosition);
            }

            fclose($handle);
            $this->logger->info('LogProcessingService: Updated line number', ['line_number' => $logFilePosition->getLineNumber()]);
        } else {
            $this->logger->error('LogProcessingService: Failed to open log file', ['filePath' => $filePath]);
        }

    }

    /**
     * @param array $logs
     * @param int $currentLineNumber
     * @param LogFilePosition $logFilePosition
     * @return void
     * @throws \Exception
     */
    private function persistLogs(array $logs, int $currentLineNumber, LogFilePosition $logFilePosition): void
    {
        foreach ($logs as $log) {
            $this->logFileRepository->insertFileLogs($log);
        }
        $logFilePosition->setLineNumber($currentLineNumber);
        $this->em->persist($logFilePosition);
        $this->em->flush();
        $this->em->clear();
    }
}