<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;


class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/allRole",
     *     tags={"Roles"},
     *     summary="Get all role",
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
    public function allRole()
    {
    $roles = Role::all();
    return response()->json(['status' => 'success', 'roles' => $roles], 200);
    }
    /**
     * @OA\Get(
     *     path="/allRole/{id}",
     *     tags={"Roles"},
     *     summary="Get role by id",
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
     *         response=404,
     *         description="Not found role"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Not authentication"
     *     ),
     * )
     */
    public function getRole($id) 
    {
        $role = Role::where('id', $id)->firstOrFail();

        return response()->json(['status' => 'success', 'role' => $role], 200);
    }
    /**
     * @OA\Post(
     *     path="/addRole/",
     *     tags={"Roles"},
     *     summary="Add new role",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
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
     *         description="Not authentication"
     *     ),
     * )
     */
    public function addRole(Request $request)
    {
        $role = Role::create($request->all());

        return response()->json(['status'  => 'success', 'role' => $role], 201);
    }
    /**
     * @OA\Put(
     *     path="/updateRole/{id}",
     *     tags={"Roles"},
     *     summary="Update role",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string"
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
     *         description="Not authentication"
     *     ),
     * )
     */
    public function updateRole(Request $request, $id)
    {

        $data = $request->all();
        $role = Role::where('id', $id)->firstOrFail();
        $role->fill($data);
        $role->save();
    
        $jsonData = [
            'status' => 'SUCCESS',
            'role' => [
                'id' => $role->id,
                'name' => $role->name
            ]
        ];

        return response()->json($jsonData);
    }
    /**
     * @OA\Delete(
     *     path="/deleteRole/{id}",
     *     tags={"Roles"},
     *     summary="Delete role",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
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
     *         description="Not authentication"
     *     ),
     * )
     */
    public function deleteRole($id): \Illuminate\Http\JsonResponse
    {
        Role::find($id)->delete();
        return response()->json(['status' => 'success', 'delete_role' => $id], 200);
    }
    /**
     * @OA\Post(
     *     path="/addRoleUser/{user_id}/{role_id}",
     *     tags={"Roles"},
     *     summary="Add roles in user",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="role_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
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
     *         description="Not authentication"
     *     ),
     * )
     */
    public function addRoleUser($user_id, $role_id) 
    {
        $user = User::where('id', $user_id)->firstOrFail();

        $user->roles()->attach($role_id);
        return response()->json(['status' => 'success', 'user' => $user_id, 'add_role' => $role_id], 200);
    }
    /**
     * @OA\Post(
     *     path="/updateRoleUser/{user_id}/{role_id}",
     *     tags={"Roles"},
     *     summary="Update roles in user",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="role_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
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
     *         description="Not authentication"
     *     ),
     * )
     */
    public function updateRoleUser($user_id, $role_id) 
    {
        $user = User::where('id', $user_id)->firstOrFail();

        $user->roles()->sync($role_id);
        return response()->json(['status' => 'success', 'user' => $user_id, 'update_role' => $role_id], 200);
    }
    /**
     * @OA\Delete(
     *     path="/deleteRoleUser/{user_id}/{role_id}",
     *     tags={"Roles"},
     *     summary="Delete roles in user",
     *     security={ {"sanctum": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="role_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string"
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
     *         description="Not authentication"
     *     ),
     * )
     */
    public function deleteRoleUser($user_id, $role_id) 
    {
        $user = User::where('id', $user_id)->firstOrFail();
        $user->roles()->detach($role_id);
        $user->roles()->detach();

        return response()->json(['status' => 'success', 'user' => $user_id, 'delete_role' => $role_id], 200);
    }
}
