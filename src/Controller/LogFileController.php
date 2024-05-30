<?php

namespace App\Controller;

use App\Repository\LogFileRepository;
use App\Service\QueryFiltersService;
use App\Service\TruncateDBService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LogFileController extends AbstractController
{
    public function __construct(private TruncateDBService $truncateDBService)
    {
    }
    #[Route('/logs/count', name: 'logs_count')]
    public function count(Request $request, LogFileRepository $logFileRepository): Response
    {
        $filters = new QueryFiltersService($request);

        $result = $logFileRepository->filterLogs($filters)
            ->select('COUNT(file_logs.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return new JsonResponse(['counter' => $result]);
    }

    #[Route('/logs/delete', name: 'logs_delete')]
    public function truncate(Request $request, TruncateDBService $truncateDBService): Response
    {
        $this->truncateDBService->proceedTruncateDBService();

        return new Response("Database truncated successfully.");
    }
}
