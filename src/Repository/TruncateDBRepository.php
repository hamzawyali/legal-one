<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class TruncateDBRepository
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @return void
     */
    public function proceedTruncateDBRepository(): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }
}