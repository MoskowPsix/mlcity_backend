<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function getLocationsIds($id) {
        $locations = Location::where('id', $id)->with('locationsChildren', 'locationParent')->first();
        return response()->json(['status' => 'success', 'location' => $locations], 200);
    }
    public function getLocationsName($name) {
        $locations = Location::orWhere('name', 'ilike', '%'.$name.'%')
        ->orWhere('name', 'ilike', '%'.$name.'%')
        ->whereHas('locationParent', function($q)use($name){
            $q->orWhere('name', 'ilike', '%'.$name.'%');
        })
        ->with('locationsChildren', 'locationParent')
        ->get();
        return response()->json(['status' => 'success', 'locations' => $locations], 200);
    }
    public function searchLocationByCoords(Request $request) {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = 5;
        $location = null;
        while(empty($location) == true) {
            $location = Location::whereRaw('(
                6371 *
                acos(cos(radians(?)) *
                cos(radians(latitude)) *
                cos(radians(longitude) -
                radians(?)) +
                sin(radians(?)) *
                sin(radians(latitude )))
            ) <= ? ',
            [$latitude, $longitude,  $latitude,  $radius])->first();
            $radius = $radius + 5;
        }
        return response()->json(['status' => 'success', 'location' => $location], 200);
    }
    public function getLocationsAll() {
        $locations = Location::where('location_id')->with('locationsChildren')->get();
        return response()->json(['status' => 'success', 'locations' => $locations], 200);
    }
    public function getLocationsAndRegion(Request $request) 
    {
        $region = Location::where('name', 'LIKE', '%' . $request->parentName . '%')->first();
        $locations = Location::where('name', 'LIKE' , '%' . $request->name . '%')->where('location_id', $region->id)->with('locationParent')->first();
        return response()->json(['status' => 'success', 'locations' => $locations], 200);
    }
}
