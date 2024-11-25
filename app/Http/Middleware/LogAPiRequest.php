<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LogAPiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // Pass the request and get the response
        $response = $next($request);

        $endTime = microtime(true);

        // Log API route requests only
        if ($request->is('api/*')) {
            DB::table('api_logs')->insert([
                'api_endpoint'    => $request->path(),
                'method'          => $request->method(),
                'request_headers' => json_encode($request->headers->all()),
                'request_body'    => json_encode($request->all()),
                'response_headers' => json_encode($response->headers->all()),
                'response_body'   => $response->getContent(),
                'status_code'     => $response->status(),
                'response_time'   => ($endTime - $startTime) * 1000, // milliseconds
                'user_agent'      => $request->header('User-Agent'),
                'ip'              => $request->ip(),
                'error'           => $response->status() >= 400 ? $response->getContent() : null,
                'user_id'         => $request->user()->id,
                'created_at'      => now(),
            ]);
        }

        return $response;
    }
}
