<?php
/**
 * @author: sma01
 * @since: 2024/6/21
 * @version: 1.0
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends BaseController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->setResponse([
            'server'   => 'cafe24app',
            'version'  => '1.0',
            'datetime' => date('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/api/key_check', name: 'api_key', methods: ['GET'])]
    public function apiKey(): JsonResponse
    {
        return $this->setResponse([
            'authorization' => 'api key',
            'datetime'      => date('Y-m-d H:i:s'),
            'user'          => $this->getUser(),
        ]);
    }
}