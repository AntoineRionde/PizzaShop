<?php

namespace pizzashop\shop\domain\service\classes;

use Firebase\JWT\JWT;


class JWTManager
{
    private $secretKey;
    private $tokenLifetime;

    public function __construct($secretKey, $tokenLifetime) {
        $this->secretKey = $secretKey;
        $this->tokenLifetime = $tokenLifetime;
    }

    public function createToken($data) {
        $issuedAt = time();
        $expire = $issuedAt + $this->tokenLifetime;

        $payload = array(
            "iss" => "pizza-shop",
            "iat" => $issuedAt,
            "exp" => $expire,
            "upr" => $data
        );

        return JWT::encode($payload, $this->secretKey);
    }

    public function validateToken($token) {
        try {
            $decoded = JWT::decode($token, $this->secretKey, array('HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }
}