<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\LogApi;
use Illuminate\Support\Facades\Log;



class RequestLoggerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, JsonResponse $response)
    {
        $user = auth('api')->user();
        $url_logs = env('APP_URL') . '/api/logs';
        
            $log_api = new LogApi();

            $log_api->url = $request->fullUrl();
            $log_api->method = $request->method();
            $log_api->request_arg = $request->collect();
            $log_api->request_header = json_encode($request->header(), true);

            if ($user){
                $log_api->user_id = $user->id;
            }

            $log_api->ip = $request->ip();
            $log_api->status_code = $response->getStatusCode();

            if (($request->fullUrl() === $url_logs) && ($response->getStatusCode() === 200)) {
                $log_api->response = '{"command":"show_log"}';
            } else {
                $log_api->response = json_encode($response->getContent(), true);
            }

            $log_api->save();
    }
}
