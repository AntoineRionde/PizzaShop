<?php

namespace pizzashop\auth\api\domain\service\classes;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;
use function DI\get;


class JWTManager
{
    private string|array|false $secretKey;
    private string|array|false $tokenLifetime;
    private string|array|false $baseUrl;

    public function __construct()
    {
        $this->secretKey = getenv('JWT_SECRET');
        $this->tokenLifetime = getenv('JWT_LIFETIME');
        $this->baseUrl = getenv('BASE_URL');
    }

    public function createToken($data): string
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->tokenLifetime;

        $payload = array(
            "iss" => $this->baseUrl,
            "iat" => $issuedAt,
            "exp" => $expire,
            "upr" => $data
        );

        return JWT::encode($payload, $this->secretKey, 'HS512');
    }

    public function validateToken($token) : array | string
    {
        try {
            $token = JWT::decode($token, new Key($this->secretKey, 'HS512'));
            return $token->payload['upr'];
        } catch (ExpiredException|SignatureInvalidException|BeforeValidException|UnexpectedValueException $e) {
            return $e->getMessage();
        }
    }
}