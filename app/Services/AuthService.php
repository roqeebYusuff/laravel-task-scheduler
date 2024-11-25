<?php

namespace App\Services;

use App\Constants;
use Carbon\Carbon;

class AuthService
{
    private $accesTokenExpireTime;
    private $refreshTokenExpireTime;

    public function __construct()
    {
        $this->accesTokenExpireTime = config('sanctum.access_token_expiration');
        $this->refreshTokenExpireTime = config('sanctum.refresh_token_expiration');
    }

    public function login($credentials) {}

    public function logout($user)
    {
        $user->tokens()->delete();

        // return
    }

    public function refresh($user)
    {
        $atExpireTime = Carbon::now()->addMinutes($this->accesTokenExpireTime);
        $accessToken = $user->createToken('access_token', [Constants::ACCESS_TOKEN], $atExpireTime);

        return $accessToken->plainTextToken;
    }

    public function me()
    {
        // return response()->json(auth()->user());
    }

    public function generateTokens($user): array
    {

        $atExpireTime = Carbon::now()->addMinutes($this->accesTokenExpireTime);
        $rtExpireTime = Carbon::now()->addMinutes($this->refreshTokenExpireTime);

        $abilities = [];

        switch ($user->user_type) {
            case 'admin':
                $abilities = ["admin"];
                break;
            case 'user':
                $abilities = ["user"];
                break;
            default:
                $abilities = [];
                break;
        }

        $accessToken = $user->createToken('access_token', $abilities, $atExpireTime);
        $refreshToken = $user->createToken('refresh_token', $abilities, $rtExpireTime);

        return [
            'accessToken' => $accessToken->plainTextToken,
            'refreshToken' => $refreshToken->plainTextToken,
        ];
    }
}
