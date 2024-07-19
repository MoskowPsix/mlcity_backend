<?php

namespace App\Http\Controllers\Api;

use App\Filters\Type\TypeName;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SightType;
use App\Models\Sight;
use Illuminate\Pipeline\Pipeline;

class SightTypeController extends Controller
{

    public function getTypes(Request $request): \Illuminate\Http\JsonResponse
    {
        if (count($request->all())>0){
            $types = SightType::query()->with('stypes');
        }
        else{
            $types = SightType::query()->with('stypes')->where('stype_id');
        }

        $response =
        app(Pipeline::class)
            ->send($types)
            ->through([
                TypeName::class
            ])
            ->via("apply")
            ->then(function($types){
                $types = $types->orderBy("name")->get();
                return $types;
            });

        return response()->json([
            'status'        => 'success',
            'types'          => $response
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
        $sight = Sight::where('id', $sight_id)->firstOrFail();

        $sight->types()->attach($type_id);
        return response()->json(['status' => 'success', 'sight' => $sight_id, 'add_type' => $type_id], 200);
    }

    public function updateTypeSight($sight_id, $type_id)
    {
        $sight = Sight::where('id', $sight_id)->firstOrFail();

        $sight->types()->sync($type_id);
        return response()->json(['status' => 'success', 'sight' => $sight_id, 'update_type' => $type_id], 200);
    }

    public function deleteTypeSight($sight_id, $type_id)
    {
        $sight = Sight::where('id', $sight_id)->firstOrFail();
        $sight->types()->detach($type_id);
        $sight->types()->detach();

        return response()->json(['status' => 'success', 'sight' => $sight_id, 'delete_type' => $type_id], 200);
    }
}
