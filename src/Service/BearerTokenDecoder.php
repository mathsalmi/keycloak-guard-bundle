<?php
/**
 * Copyright (C) A&C systems nv - All Rights Reserved
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by A&C systems <web.support@ac-systems.com>
 */

namespace ACSystems\KeycloakGuardBundle\Service;

use ACSystems\KeycloakGuardBundle\Exception\JWTDecoderException;
use ACSystems\KeycloakGuardBundle\Provider\JwkProviderInterface;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class BearerTokenDecoder
 * @package ACSystems\KeycloakGuardBundle\Service
 */
class BearerTokenDecoder implements TokenDecoderInterface
{
    /**
     * @var JwkProviderInterface
     */
    private $jwkProvider;

    /**
     * BearerTokenDecoder constructor.
     * @param JwkProviderInterface $jwkProvider
     */
    public function __construct(JwkProviderInterface $jwkProvider)
    {
        $this->jwkProvider = $jwkProvider;
    }

    /**
     * @param string $token
     * @return array
     * @throws Exception
     */
    public function decodeToken(string $token): array
    {
        $jwt = preg_replace('/^Bearer\s/', '', $token);
        $encodedJwtHead = substr($jwt, 0, strpos($jwt, '.'));
        $jwtHeader = json_decode(base64_decode($encodedJwtHead), true);

        if (empty($jwtHeader['kid']) || empty($jwtHeader['alg'])) {
            throw new JWTDecoderException('Invalid kid or algorithm');
        }

        $keyId = $jwtHeader['kid'];

        try {
            $jwks = $this->jwkProvider->getJwks($token);
            if (!isset($jwks[$keyId])) {
                $jwks = $this->jwkProvider->getJwks($token, false);
                if (!isset($jwks[$keyId])) {
                    throw new JWTDecoderException('No key found for decoding the token');
                }
            }

            $decoded = JWT::decode($jwt, $jwks[$keyId], [$jwtHeader['alg']]);
            return json_decode(json_encode($decoded), true);
        } catch (ExpiredException $ex) {
            throw new AccessDeniedHttpException('Token expired');
        } catch (SignatureInvalidException $ex) {
            throw new AccessDeniedHttpException('Invalid signature');
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
