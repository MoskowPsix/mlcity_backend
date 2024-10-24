<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;

class SeanceController extends Controller
{
    public function getSeancesAtPlaceIds($id) {

        $place = Place::where('id', $id)->firstOrFail()->seances()->get();
        return response()->json(['status'=> 'success','seances'=> $place ], 200);
    }
}
