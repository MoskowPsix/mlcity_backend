<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;


class RoleController extends Controller
{
    public function allRole()
    {
    return Role::all();
    }

    public function getRole($id) 
    {
        $role = Role::where('id', $id)->firstOrFail();

        return response()->json(['status' => 'success', 'role' => $role], 200);
    }

    public function addRole(Request $request)
    {
        $role = Role::create($request->all());

        return response()->json(['status'  => 'success', 'role' => $role], 201);
    }

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

    public function deleteRole($id): \Illuminate\Http\JsonResponse
    {
        Role::find($id)->delete();
        return response()->json(['status' => 'success', 'delete_role' => $id], 200);
    }

    public function addRoleUser($user_id, $role_id) 
    {
        $user = User::where('id', $user_id)->firstOrFail();

        $user->roles()->attach($role_id);
        return response()->json(['status' => 'success', 'user' => $user_id, 'add_role' => $role_id], 200);
    }

    public function updateRoleUser($user_id, $role_id) 
    {
        $user = User::where('id', $user_id)->firstOrFail();

        $user->roles()->sync($role_id);
        return response()->json(['status' => 'success', 'user' => $user_id, 'update_role' => $role_id], 200);
    }

    public function deleteRoleUser($user_id, $role_id) 
    {
        $user = User::where('id', $user_id)->firstOrFail();
        $user->roles()->detach($role_id);
        $user->roles()->detach();

        return response()->json(['status' => 'success', 'user' => $user_id, 'delete_role' => $role_id], 200);
    }
}
