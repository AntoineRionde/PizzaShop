<?php

namespace pizzashop\auth\api\domain\service\classes;

use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use pizzashop\auth\api\domain\exceptions\TokenException;
use UnexpectedValueException;


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

    /**
     * @throws TokenException
     */
    public function createToken($data): string
    {
        try {
            $issuedAt = time();
            $expire = $issuedAt + $this->tokenLifetime;

            $payload = array(
                "iss" => $this->baseUrl,
                "iat" => $issuedAt,
                "exp" => $expire,
                "upr" => $data
            );

            return JWT::encode($payload, $this->secretKey, 'HS256');
        } catch (Exception) {
            throw new TokenException('Error during token creation');
        }
    }

    /**
     * @throws SignatureInvalidException|BeforeValidException|ExpiredException|UnexpectedValueException
     */
    public function validateToken($token): array
    {
        try {
            $token = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return [$token->upr->username, $token->upr->email];
        } catch (SignatureInvalidException) {
            throw new SignatureInvalidException("Invalid signature");
        } catch (BeforeValidException) {
            throw new BeforeValidException("Token is not valid yet");
        } catch (ExpiredException) {
            throw new ExpiredException("Token has expired");
        } catch (UnexpectedValueException) {
            throw new UnexpectedValueException("Unexpected value");
        }
    }
}