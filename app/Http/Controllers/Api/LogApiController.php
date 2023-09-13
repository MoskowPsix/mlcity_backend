<?php

namespace App\Http\Controllers\Api;


use App\Filters\LogApi\LogApiIp;
use App\Filters\LogApi\LogApiMethod;
use App\Filters\LogApi\LogApiRequest;
use App\Filters\LogApi\LogApiResponse;
use App\Filters\LogApi\LogApiStatusCode;
use App\Filters\LogApi\LogApiUserId;
use App\Filters\LogApi\LogApiDate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use App\Filters\LogApi\LogApiUrl;
use App\Models\LogApi;
use Illuminate\Support\Facades\Crypt;

class LogApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/logs",
     *     tags={"Logs"},
     *     summary="Check logs api",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="url",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="ip",
     *         in="query",
     *         @OA\Schema(
     *             type="ip"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="method",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="request",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="status_code",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="response",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="dateStart",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="dateEnd",
     *         in="query",
     *         @OA\Schema(
     *             type="date"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="not authentication"
     *     ),
     * )
     */
    public function getLogs(Request $request)
    {
        $url = $request->fullUrl();
        $limit = $request->limit ? $request->limit : 10;
        $logs = LogApi::query()->with('logUser');

        // $i = 0;
        // foreach($logs as $log) {
        //     $logs[$i]->url = decrypt($logs[$i]->url);
        //     // $response->$logs[$i]->request_arg = (string)Crypt::decryptString( $response->$logs[$i]->request_arg);
        //     // $response->$logs[$i]->request_header = (string)Crypt::decryptString( $response->$logs[$i]->request_header);
        //     $i++;
        // }

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
                LogApiDate::class
            ])
            ->via('apply')
            ->then(function ($logs) use ($limit){
                return $logs->orderBy('created_at','desc')->cursorPaginate($limit)->appends(request()->except('page'));
            });

        return response()->json(['status' => 'success', 'logs' => $response, 'current_url' => $url], 200);
    }
}