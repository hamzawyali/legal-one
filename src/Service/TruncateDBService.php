<?php

namespace App\Service;

use App\Repository\TruncateDBRepository;

class TruncateDBService
{
    /**
     * @param TruncateDBRepository $truncateDBRepository
     */
    public function __construct(private TruncateDBRepository $truncateDBRepository)
    {

    }

    /**
     * @return TruncateDBRepository
     */
    public function proceedTruncateDBService(): null
    {
        return $this->truncateDBRepository->proceedTruncateDBRepository();
    }
}