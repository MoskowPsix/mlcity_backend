<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SightType;
use App\Models\Sight;


class SightTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/sight-types",
     *     tags={"Sight-type"},
     *     summary="Get all sight type",
     *     security={ {"sanctum": {} }},
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
    public function getTypes(): \Illuminate\Http\JsonResponse
    {
        $types = SightType::all();

        return response()->json([
            'status'        => 'success',
            'types'          => $types
        ], 200);
    }
    /**
     * @OA\Get(
     *     path="/sights/getTypesId/{id}",
     *     tags={"Sight-type"},
     *     summary="Get sight type by id",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
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
    public function getTypesId($id): \Illuminate\Http\JsonResponse
    {
        $types = SightType::where('id', $id)->firstOrFail();

        return response()->json([
            'status'        => 'success',
            'types'          => $types
        ], 200);
    }
    /**
     * @OA\Post(
     *     path="/sights/addTypeSight/{sight_id}/{type_id}",
     *     tags={"Sight-type"},
     *     summary="add type in sight",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="type_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="$sight_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
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
    public function addTypeSight($sight_id, $type_id) 
    {
        $event = Sight::where('id', $sight_id)->firstOrFail();

        $event->types()->attach($type_id);
        return response()->json(['status' => 'success', 'sight' => $sight_id, 'add_type' => $type_id], 200);
    }
    /**
     * @OA\Put(
     *     path="/sights/updateTypeSight/{sight_id}/{type_id}",
     *     tags={"Sight-type"},
     *     summary="add type in event",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="type_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="$sight_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
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
    public function updateTypeSight($sight_id, $type_id) 
    {
        $event = Sight::where('id', $sight_id)->firstOrFail();

        $event->types()->sync($type_id);
        return response()->json(['status' => 'success', 'sight' => $sight_id, 'update_type' => $type_id], 200);
    }
    /**
     * @OA\Delete(
     *     path="/sights/deleteTypeSight/{sight_id}/{type_id}",
     *     tags={"Sight-type"},
     *     summary="add type in sight",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="type_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="$sight_id",
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
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
    public function deleteTypeSight($sight_id, $type_id) 
    {
        $event = Sight::where('id', $sight_id)->firstOrFail();
        $event->types()->detach($type_id);
        $event->types()->detach();

        return response()->json(['status' => 'success', 'sight' => $sight_id, 'delete_type' => $type_id], 200);
    }
}
