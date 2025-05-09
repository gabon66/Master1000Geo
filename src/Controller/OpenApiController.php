<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OpenApiController extends AbstractController
{
    #[Route('/api/doc.json', name: 'api_openapi_json', methods: ['GET'])]
    public function index(): Response
    {
        $path = __DIR__ . '/../../openapi.json';
        $data = json_decode(file_get_contents($path), true);

        return new JsonResponse($data);
    }
}
