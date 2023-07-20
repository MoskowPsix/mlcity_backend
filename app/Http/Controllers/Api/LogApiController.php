<?php

namespace App\Http\Controllers\Api;


use App\Filters\LogApi\LogApiIp;
use App\Filters\LogApi\LogApiMethod;
use App\Filters\LogApi\LogApiRequest;
use App\Filters\LogApi\LogApiResponse;
use App\Filters\LogApi\LogApiStatusCode;
use App\Filters\LogApi\LogApiUserId;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use App\Filters\LogApi\LogApiUrl;
use App\Models\LogApi;

class LogApiController extends Controller
{
    public function getLogs(Request $request)
    {
        $page = $request->page;
        $limit = $request->limit ? $request->limit : 10;
        $logs = LogApi::query()->with('logUser');

        $response =
            app(Pipeline::class)
            ->send($logs)
            ->through([
                LogApiUrl::class,
                LogApiIp::class,
                LogApiMethod::class,
                LogApiRequest::class,
                LogApiStatusCode::class,
                LogApiResponse::class,
                LogApiUserId::class,
            ])
            ->via('apply')
            ->then(function ($logs) use ($page, $limit){
                return $logs->orderBy('created_at','desc')->paginate($limit, ['*'], 'page' , $page)->appends(request()->except('page'));
            });
        return response()->json(['status' => 'success', 'logs' => $response], 200);
    }
}