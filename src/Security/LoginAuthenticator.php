<?php
/**
 * @author: sma01
 * @since: 2024/6/20
 * @version: 1.0
 */
namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Symfony\Component\HttpFoundation\Request;

class LoginAuthenticator extends JWTAuthenticator
{
    public const LOGIN_URL = '/api/login_check';

    public const REFRESH_TOKEN_URL = '/api/token/refresh';

    public function supports(Request $request): ?bool
    {
        if ($request->getPathInfo() === self::LOGIN_URL || $request->getPathInfo() === self::REFRESH_TOKEN_URL) {
            return false;
        }

        return false !== $this->getTokenExtractor()->extract($request);
    }
}
