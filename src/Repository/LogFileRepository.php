<?php

namespace App\Repository;

use App\Entity\LogFile;
use App\Service\QueryFiltersService;
use App\Trait\FilterTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<LogFile>
 */
class LogFileRepository extends ServiceEntityRepository
{
    use FilterTrait;

    /**
     * @param ManagerRegistry $registry
     * @param LoggerInterface $logger
     */
    public function __construct(ManagerRegistry $registry, private LoggerInterface $logger)
    {
        parent::__construct($registry, LogFile::class);
    }

    /**
     * @param array $logData
     * @return void
     * @throws \Exception
     */
    public function insertFileLogs(array $logData): void
    {
        // Debugging: Log the type and contents of $logData
        $this->logger->info('saveLog called', ['logDataType' => gettype($logData), 'logData' => $logData]);

        // Check if logData is an array
        if (!is_array($logData)) {
            throw new \InvalidArgumentException('Expected array for $logData');
        }

        $requiredKeys = ['serviceName', 'dateTime', 'statusCode'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $logData)) {
                throw new \InvalidArgumentException("Missing key '$key' in log data");
            }
        }

        $entityManager = $this->getEntityManager();

        $logFile = new LogFile();
        $logFile->setServiceName($logData['serviceName']);
        $logFile->setStatusCode($logData['statusCode']);
        // Convert string to DateTime object
        $logFile->setDateTime(new DateTime($logData['dateTime']));

        $entityManager->persist($logFile);
        $entityManager->flush();
        $entityManager->clear();

        // Debugging: Confirm log has been persisted
        $this->logger->info('LogFile entity persisted', ['logFile' => $logFile]);
    }

    /**
     * @param QueryFiltersService $filters
     * @return QueryBuilder
     */
    public function filterLogs(QueryFiltersService $filters): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('file_logs');
        return $this->applyFilters($queryBuilder, $filters);
    }
}
