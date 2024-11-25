<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    public function sendResponse(bool $success, int $statusCode, string $message = '', $data = null, $errors = null): JsonResponse
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        if (!is_null($errors)) {
            $response['error'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
