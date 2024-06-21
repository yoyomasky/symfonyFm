<?php
/**
 * @author: sma01
 * @since: 2024/6/20
 * @version: 1.0
 */


namespace App\Security;

use App\Service\ApiKeyJwtService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator
{

    private ApiKeyJwtService $jwtService;
    private UserProviderInterface $userProvider;

    public function __construct(ApiKeyJwtService $jwtService, UserProviderInterface $userProvider)
    {
        $this->jwtService   = $jwtService;
        $this->userProvider = $userProvider;
    }

    public function supports(Request $request): ?bool
    {
        return false !== $request->headers->has('x-api-key');
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get('x-api-key');
        $this->jwtService->validateToken($token);
        $username = $this->jwtService->getIdentified($token);
        $passport = new SelfValidatingPassport(
            new UserBadge(
                $username,
                function () use ($username) {
                    return $this->userProvider->loadUserByIdentifier($username);
                }
            )
        );
        $passport->setAttribute('token', $token);
        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }

//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}
