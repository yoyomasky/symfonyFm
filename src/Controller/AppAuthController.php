<?php
/**
 * @author: sma01
 * @since: 2024/6/24
 * @version: 1.0
 */
namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AuthService;
use App\Repository\Main\UserRepository;
use App\Entity\Main\User;

class AppAuthController extends BaseController
{


    #[Route('/connect/cafe24', name: 'connect_cafe24')]
    public function connectAction(Request $request, AuthService $authService) : JsonResponse | RedirectResponse
    {
        $mallId = $request->query->get('mall_id');

        // OAuth 流程由 AuthService 处理
        return $authService->redirectToOAuth($mallId);
    }

    #[Route('/connect/cafe24/check', name: 'cafe24_check')]
    public function connectCheckAction(Request $request, UserRepository $userRepository, AuthService $authService) : JsonResponse
    {
        $mallId = $request->query->get('mall_id');

        // 获取并存储访问令牌
        $accessToken = $authService->fetchAndStoreAccessToken($mallId);

        // 查找用户
        $user = $userRepository->findOneBy(['mallId' => $mallId]);

        if (!$user) {
            // 如果用户不存在，创建新用户
            $user = new User();
            $user->setMallId($mallId);
            // 其他初始化逻辑
        }

        // 更新用户的访问令牌
        // 你可以选择将访问令牌与用户关联存储在数据库中
        // $user->setAccessToken($accessToken);
        $userRepository->save($user);


        return $this->setResponse([
            'message'   => 'OAuth 2.0 认证完成',
            'datetime' => date('Y-m-d H:i:s'),
        ]);
    }
}