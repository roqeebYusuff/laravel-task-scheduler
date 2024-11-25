<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class ApiLogController extends BaseController
{
    public function logRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'api_endpoint' => 'required|string',
            'method' => 'required|string',
            'request_headers' => 'nullable|array',
            'response_headers' => 'nullable|array',
            'response_body' => 'nullable|array',
            'request_body' => 'nullable|array',
            'ip' => 'nullable|ip',
            'user_agent' => 'required|string',
            'status_code' => 'required|integer',
            'response_time' => 'required|numeric',
            'error' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, StatusCode::HTTP_BAD_REQUEST, 'Validation failed', null, $validator->errors());
        }

        $log = $validator->validated();

        ApiLog::create($log);
        return $this->sendResponse(true, StatusCode::HTTP_CREATED, 'Log created successfully', $log);
    }

    public function getLogs(Request $request)
    {
        $filters = $request->only(['search']);
        $sorts = $request->only(['sort', 'order']);
        $perPage = $request->get('per_page', 15);

        $logs = ApiLog::getLogs($filters, $sorts, $perPage);

        return $this->sendResponse(true,  StatusCode::HTTP_CREATED, 'Logs retrieved successfully', $logs);
    }

    public function getLog($id)
    {
        $log = ApiLog::getLog($id);

        if (!$log) {
            return $this->sendResponse(false, StatusCode::HTTP_BAD_REQUEST, 'Log not found', null);
        }

        return $this->sendResponse(true, StatusCode::HTTP_CREATED, 'Log retrieved successfully', $log);
    }
}
