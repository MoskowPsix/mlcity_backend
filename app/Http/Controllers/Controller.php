<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     description="API MlCity",
 *     version="1.0.0",
 *     title="API Docs MlCity Project"
 * ),
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server",
 * ),
 * 
 */


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
}
