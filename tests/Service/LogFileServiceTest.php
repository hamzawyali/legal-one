<?php

use PHPUnit\Framework\TestCase;
use App\Service\LogFileService;
use App\Repository\LogFilePositionRepository;
use App\Repository\LogFileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\LogFilePosition;

class LogFileServiceTest extends TestCase
{
    private $logFileRepositoryMock;
    private $logFilePositionRepositoryMock;
    private $entityManagerMock;
    private $loggerMock;
    private $logFileService;

    protected function setUp(): void
    {
        $this->logFileRepositoryMock = $this->createMock(LogFileRepository::class);
        $this->logFilePositionRepositoryMock = $this->createMock(LogFilePositionRepository::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->logFileService = new LogFileService(
            $this->logFileRepositoryMock,
            $this->logFilePositionRepositoryMock,
            $this->entityManagerMock,
            $this->loggerMock
        );
    }

    public function testProcessLogFile()
    {
        $filePath = 'logs.log';
        $logFilePositionMock = $this->createMock(LogFilePosition::class);

        $this->logFilePositionRepositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['filePath' => $filePath])
            ->willReturn($logFilePositionMock);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('persist')
            ->with($logFilePositionMock);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $this->entityManagerMock
            ->expects($this->once())
            ->method('clear');

        $this->loggerMock
            ->expects($this->once())
            ->method('info')
            ->with('LogProcessingService: Updated line number', ['line_number' => $logFilePositionMock->getLineNumber()]);

        $this->logFileService->processLogFile($filePath);
    }
}