<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\Security;

use ACSystems\JWTAuthBundle\Validator\BearerTokenValidator;
use ACSystems\KeycloakGuardBundle\Service\KeycloakParsedTokenFactory;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class TokenAuthenticator
 * @package ACSystems\KeycloakGuardBundle\Security
 */
class KeycloakTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var KeycloakParsedTokenFactory
     */
    private $parsedTokenFactory;

    /**
     * @var BearerTokenValidator
     */
    private $tokenValidator;

    /**
     * KeycloakTokenAuthenticator constructor.
     * @param KeycloakParsedTokenFactory $parsedTokenFactory
     * @param BearerTokenValidator $tokenValidator
     */
    public function __construct(
        KeycloakParsedTokenFactory $parsedTokenFactory,
        BearerTokenValidator $tokenValidator
    ) {
        $this->parsedTokenFactory = $parsedTokenFactory;
        $this->tokenValidator = $tokenValidator;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        $authHeader = $request->headers->get('Authorization');
        return !empty($authHeader);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        return [
            'token' => $request->headers->get('Authorization')
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $provider
     * @return UserInterface
     * @throws Exception
     */
    public function getUser($credentials, UserProviderInterface $provider): UserInterface
    {
        $token = $credentials['token'] ?? null;
        $decodedToken = $this->tokenValidator->decodeAuthorizationToken($token);

        return $token === null
            ? null
            : $this->parsedTokenFactory->createFromToken($decodedToken);
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param $providerKey
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): void
    {
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|void|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new JsonResponse(['error' => $exception->getMessage(), Response::HTTP_UNAUTHORIZED]);
    }

    /**
     * Called when authentication is needed, but it's not sent
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return bool
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}