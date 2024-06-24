<?php
/**
 * @author: sma01
 * @since: 2024/6/22
 * @version: 1.0
 */

namespace App\Controller;

use App\Controller\BaseController;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Main\UserRepository;

class InstallController extends BaseController
{

    #[Route('/init', name: 'app_init')]
    public function index(Request $request, UserRepository $userRepository, AuthService $authService): JsonResponse | RedirectResponse
    {
        // 假设 mall_id 通过查询参数传递
        $mallId = $request->query->get('mall_id');
        $user = $userRepository->findOneBy(['mallId' => $mallId]);

        // 检查 Redis 中是否有访问令牌
        $accessToken = $authService->validateAccessToken($mallId);

        if (!$accessToken) {
            return $authService->redirectToOAuth($mallId);
        }

        return $this->setResponse([
            'server'   => 'cafe24app',
            'version'  => '1.0',
            'datetime' => date('Y-m-d H:i:s'),
        ]);
    }

    private function isAccessTokenValid($accessToken): bool
    {
        // 这里需要实现令牌有效性的检查
        // 例如，检查令牌是否存在，是否过期等
        return !empty($accessToken);
    }
}