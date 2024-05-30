<?php

namespace App\Contract;

use Doctrine\ORM\QueryBuilder;

interface QueryFiltersServiceInterface
{
    public function apply(QueryBuilder $queryBuilder): QueryBuilder;

    public function filters(): array;

    public function serviceName($serviceName): void;

    public function statusCode($statusCode): void;

    public function startDate($startDate): QueryBuilder;

    public function endDate($endDate): QueryBuilder;

}