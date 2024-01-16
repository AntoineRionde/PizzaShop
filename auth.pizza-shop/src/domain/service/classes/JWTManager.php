<?php

namespace pizzashop\auth\api\domain\service\classes;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;


class JWTManager
{
    private $secretKey;
    private $tokenLifetime;

    public function __construct($secretKey, $tokenLifetime)
    {
        $this->secretKey = $secretKey;
        $this->tokenLifetime = $tokenLifetime;
    }

    public function createToken($data): string
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->tokenLifetime;

        $payload = array(
            "iss" => "http://localhost:2080",
            "iat" => $issuedAt,
            "exp" => $expire,
            "upr" => $data
        );

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function validateToken($token) : array | string
    {
        try {
            $token = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return $token->payload['upr'];
        } catch (ExpiredException|SignatureInvalidException|BeforeValidException|UnexpectedValueException $e) {
            return $e->getMessage();
        }
    }
}