<?php
/**
 * @author: sma01
 * @since: 2024/6/20
 * @version: 1.0
 */


namespace App\Service;

use App\Const\ResponseMessage;
use App\Exception\CustomException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class ApiKeyJwtService
{
    private Configuration $config;

    public function __construct(string $secretKey)
    {
        $this->config = Configuration::forSymmetricSigner(new Sha256(), InMemory::base64Encoded($secretKey));
    }

    public function createToken($username): string
    {
        try {
            $now   = new \DateTimeImmutable();
            $token = $this->config->builder()
                ->issuedAt($now)
                ->expiresAt($now->modify('+10 minute'))
                ->withClaim('username', $username)
                ->getToken($this->config->signer(), $this->config->signingKey());
            return $token->toString();
        } catch (\Exception $exception) {
            throw new CustomException(ResponseMessage::SECURITY_JWT_CREATE_FAIL);
        }
    }

    public function validateToken(string $jwt): bool
    {
        try {
            $token = $this->config->parser()->parse($jwt);
        } catch (\Exception $exception) {
            throw new CustomException(ResponseMessage::SECURITY_JWT_AUTH_FAIL);
        }
        $claims = $token->claims();
        if (!$claims->get('username')) {
            throw new CustomException(ResponseMessage::SECURITY_JWT_INVALID);
        }
        try {
            /* @var \DateTimeImmutable $iat */
            /* @var \DateTimeImmutable $exp */
            $iat = $claims->get('iat')->getTimestamp();
            $exp = $claims->get('exp')->getTimestamp();
            $now = (new \DateTimeImmutable('now', new \DateTimeZone('Asia/Seoul')))->getTimestamp();
            if ($now < $iat || $now > $exp || $exp > ($iat + 10 * 60)) {
                throw new \Exception();
            }
        } catch (\Exception $exception) {
            throw new CustomException(ResponseMessage::SECURITY_JWT_EXPIRE);
        }
        return true;
    }

    public function getIdentified($jwt)
    {
        $token = $this->config->parser()->parse($jwt);
        return $token->claims()->get('username');
    }
}
