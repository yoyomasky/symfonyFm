<?php
/**
 * @author: sma01
 * @since: 2024/6/24
 * @version: 1.0
 */

namespace App\Service;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class AuthService
{
    private $redisTokenService;
    private $clientRegistry;
    private $router;

    public function __construct(
        RedisTokenService $redisTokenService,
        ClientRegistry $clientRegistry,
        RouterInterface $router
    ) {
        $this->redisTokenService = $redisTokenService;
        $this->clientRegistry = $clientRegistry;
        $this->router = $router;
    }

    public function validateAccessToken(string $mallId): ?string
    {
        $accessToken = $this->redisTokenService->getAccessToken($mallId);

        if (!$accessToken) {
            return null;
        }

        // 你可以在这里添加其他令牌有效性检查，例如过期检查

        return $accessToken;
    }

    public function redirectToOAuth(string $mallId): RedirectResponse
    {
        return new RedirectResponse($this->router->generate('connect_cafe24', ['mall_id' => $mallId]));
    }

    public function fetchAndStoreAccessToken(string $mallId): string
    {
        $client = $this->clientRegistry->getClient('cafe24');
        $accessToken = $client->getAccessToken([
            'query' => [
                'mall_id' => $mallId,
            ],
        ]);

        // 存储访问令牌
        $this->redisTokenService->setAccessToken($mallId, $accessToken->getToken(), $accessToken->getExpires() - time());

        return $accessToken->getToken();
    }
}