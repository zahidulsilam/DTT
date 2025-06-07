<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\ApiModel;
use App\Plugins\Http\Response as Status;
use Exception;

class TokeService
{

    private $apiModel;
    private $secretKey;

    function __construct()
    {
        $this->apiModel = new ApiModel();
        $this->secretKey = 'ofWcar+QaUfvQI03hA3zBLVa8ADvdx9MT9GrmBlJWCg=';
    }

    /**
     * generate token for authenciate
     *
     * @param [type] $userId
     * @return string
     */
    function generateToken($userId): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 86400;  // jwt valid for 1 hour from the issued time
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $userId
        ];
        $secretKey = $this->secretKey;
        $algorithm = 'HS256';
        $token = JWT::encode($payload, $secretKey, $algorithm);
        $this->apiModel->updateToken($userId, $token);
        return $token;
    }

    /**
     * generete secret key for bearer token
     *
     * @return string
     */
    private function generateSecreteKey(): string
    {
        $secretKey = base64_encode(random_bytes(32));
        return $secretKey;
    }

    /**
     * token verify
     *
     * @return boolean
     */
    function verifyToken(): bool
    {
        $secretKey = $this->secretKey;
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $jwt = $matches[1];
            try {
                $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
                return true;
            } catch (Exception $e) {
                (new Status\Unauthorized([['message' => $e->getMessage(), 'data' => []]]))->send();
                exit();
            }
        } else {
            (new Status\BadRequest([['message' => 'Header not found in request', 'data' => []]]))->send();
            exit();
        }
    }
}
