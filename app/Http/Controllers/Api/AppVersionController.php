<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Google\Service\AndroidPublisher\AppEdit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Google_Client;
use Google_Service_AndroidPublisher;
use Illuminate\Support\Facades\Http;


class AppVersionController extends Controller
{
    public function setVersion(string $platform, string $number): JsonResponse
    {
        $app = AppVersion::where('platform', $platform);
        if(!$app->exists())
        {
            return response()->json([
                'status'=>'error',
                'message'=>'App version not found',
            ]);
        }

        $app->update([
            'version' => $number
        ]);

        return response()->json([
            'status'=>'success',
            'message'=>'App version updated successfully',
        ], 200);
    }

    public function getVersion(): JsonResponse
    {
        $app = AppVersion::all();

        return response()->json([
            'status'=>'success',
            'message'=>'App versions retrieved successfully',
            'data'=>$app
        ]);
    }
}
