<?php

namespace App\Controller;

use App\Repository\LogFileRepository;
use App\Service\QueryFiltersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LogFileController extends AbstractController
{
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
}
