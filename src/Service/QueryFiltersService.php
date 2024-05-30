<?php

namespace App\Service;

use App\Contract\QueryFiltersServiceInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class QueryFiltersService implements QueryFiltersServiceInterface
{
    protected QueryBuilder $queryBuilder;

    /**
     * @param Request $request
     */
    public function __construct(protected Request $request)
    {
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     */
    public function apply(QueryBuilder $queryBuilder): QueryBuilder
    {
        $this->queryBuilder = $queryBuilder;
        foreach ($this->filters() as $name => $value) {
            if (!method_exists($this, $name)) {
                continue;
            }
            if (strlen($value)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }

        return $this->queryBuilder;
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return $this->request->query->all();
    }

    /**
     * @param $serviceName
     * @return void
     */
    public function serviceName($serviceName): void
    {
        $this->queryBuilder->andWhere('file_logs.serviceName LIKE :serviceName')->setParameter('serviceName', "%$serviceName%");
    }

    /**
     * @param $statusCode
     * @return void
     */
    public function statusCode($statusCode): void
    {
        $this->queryBuilder->andWhere('file_logs.statusCode = :statusCode')->setParameter('statusCode', $statusCode);
    }

    /**
     * @param $startDate
     * @return QueryBuilder
     */
    public function startDate($startDate): QueryBuilder
    {
        return $this->queryBuilder->where('file_logs.dateTime >= :startDate')->setParameter('startDate', $startDate);
    }

    /**
     * @param $endDate
     * @return QueryBuilder
     */
    public function endDate($endDate): QueryBuilder
    {
        // Assuming $endDate is in the format 'Y-m-d'
        $endDateTime = strtotime($endDate . ' 23:59:59');

        // Format the end date to match the database format (assuming it's a datetime field)
        $endDateTimeFormatted = date('Y-m-d H:i:s', $endDateTime);

        return $this->queryBuilder->andWhere('file_logs.dateTime <= :endDateTimeFormatted')->setParameter('endDateTimeFormatted', $endDateTimeFormatted);
    }
}