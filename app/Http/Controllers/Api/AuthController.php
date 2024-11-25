<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as StatusCode;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    private $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, StatusCode::HTTP_BAD_REQUEST, 'Validation failed', null, $validator->errors());
        }

        $input = $request->only(['name', 'email', 'password']);

        $user = User::create($input);

        $tokens = $this->service->generateTokens($user);

        $data = [
            'name' => $user->name,
            ...$tokens,
        ];

        return $this->sendResponse(true, StatusCode::HTTP_CREATED, 'User registered successfully.', $data);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, StatusCode::HTTP_BAD_REQUEST, 'Validation failed', null, $validator->errors());
        }

        $input = $request->only(['email', 'password']);

        if (!Auth::attempt($input)) {
            return $this->sendResponse(false, StatusCode::HTTP_UNAUTHORIZED, 'Unauthorized', null);
        }

        $user = Auth::user();

        $tokens = $this->service->generateTokens($user);

        $data = [
            'name' => $user->name,
            ...$tokens,
        ];

        return $this->sendResponse(true, StatusCode::HTTP_OK, 'User logged in successfully.', $data);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();
        $this->service->logout($user);
        return $this->sendResponse(true, StatusCode::HTTP_OK, 'User logged out successfully.');
    }

    public function refresh(Request $request): JsonResponse
    {
        $user = Auth::user();
        $accessToken = $this->service->refresh($user);
        return $this->sendResponse(true, StatusCode::HTTP_OK, 'Token refreshed successfully.', ['access_token' => $accessToken]);
    }
}
