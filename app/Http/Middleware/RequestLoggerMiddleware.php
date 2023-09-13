<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\LogApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;



class RequestLoggerMiddleware
{
    private $record_id;
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        $url_logs = env('APP_URL') . '/api/logs';
        $user = auth('api')->user();

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
        $log_api->response = ((strpos($request->fullUrl(), $url_logs) !== false) and ($response->getStatusCode() === 200)) ? '{"command":"show_log"}' : json_encode($response->getContent(), true);
        $log_api->save();
    }
}
