<?php

namespace App\Trait;

use App\Service\QueryFiltersService;
use Doctrine\ORM\QueryBuilder;

trait FilterTrait
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param QueryFiltersService $filters
     * @return QueryBuilder
     */
    public function applyFilters(QueryBuilder $queryBuilder, QueryFiltersService $filters): QueryBuilder
    {
        return $filters->apply($queryBuilder);
    }
}