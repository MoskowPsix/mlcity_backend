<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SightType;
use App\Models\Sight;


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

    public function getTypesId($id): \Illuminate\Http\JsonResponse
    {
        $types = SightType::where('id', $id)->firstOrFail();

        return response()->json([
            'status'        => 'success',
            'types'          => $types
        ], 200);
    }
    public function addTypeSight($sight_id, $type_id) 
    {
        $event = Sight::where('id', $sight_id)->firstOrFail();

        $event->types()->attach($type_id);
        return response()->json(['status' => 'success', 'sight' => $sight_id, 'add_type' => $type_id], 200);
    }
    public function updateTypeSight($sight_id, $type_id) 
    {
        $event = Sight::where('id', $sight_id)->firstOrFail();

        $event->types()->sync($type_id);
        return response()->json(['status' => 'success', 'sight' => $sight_id, 'update_type' => $type_id], 200);
    }
    public function deleteTypeSight($sight_id, $type_id) 
    {
        $event = Sight::where('id', $sight_id)->firstOrFail();
        $event->types()->detach($type_id);
        $event->types()->detach();

        return response()->json(['status' => 'success', 'sight' => $sight_id, 'delete_type' => $type_id], 200);
    }
}
