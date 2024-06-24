<?php
/**
 * @author: sma01
 * @since: 2024/6/24
 * @version: 1.0
 */
namespace App\Service;

use Symfony\Component\Cache\Adapter\RedisAdapter;

class RedisTokenService
{
    private $redis;

    public function __construct()
    {
        $this->redis = RedisAdapter::createConnection('redis://localhost');
    }

    public function getAccessToken(string $mallId): ?string
    {
        return $this->redis->get("access_token:{$mallId}") ?: null;
    }

    public function setAccessToken(string $mallId, string $token, int $ttl = 3600)
    {
        $this->redis->set("access_token:{$mallId}", $token, $ttl);
    }

    public function deleteAccessToken(string $mallId)
    {
        $this->redis->del(["access_token:{$mallId}"]);
    }
}