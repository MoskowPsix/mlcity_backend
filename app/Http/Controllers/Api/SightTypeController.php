<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SightType;

class SightTypeController extends Controller
{
    public function getTypes(): \Illuminate\Http\JsonResponse
    {
        $types = SightType::all();

        return response()->json([
            'status'        => 'success',
            'types'          => $types
        ], 200);
    }
}
