<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function getLocationsIds($id) {
        $locations = Location::where('id', $id)->with('locationsChildren', 'locationParent')->first();
        return response()->json(['status' => 'success', 'location' => $locations], 200);
    }
    public function getLocationsName($name) {
        $locations = Location::where('name', 'LIKE', '%'.$name.'%')->with('locationsChildren', 'locationParent')->get();
        return response()->json(['status' => 'success', 'locations' => $locations], 200);
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
