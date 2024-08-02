<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;

#[Group(name: 'Seance', description: 'Методы для управления сенсами мест проведения событий')]
class SeanceController extends Controller
{
    public function getSeancesAtPlaceIds($id) {

        $place = Place::where('id', $id)->firstOrFail()->seances()->get();
        return response()->json(['status'=> 'success','seances'=> $place ], 200);
    }
}
