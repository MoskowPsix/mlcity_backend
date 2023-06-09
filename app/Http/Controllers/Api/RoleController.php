<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;


class RoleController extends Controller
{
    public function getRole()
    {
    return Role::all();
    }

    public function addRole(Request $request)
    {
        $role = Role::create($request->all());

        return response()->json(['status'  => 'success', 'role' => $role], 201);
    }

    public function updateRole(Request $request, Role $role)
    {
        $role->update($request->all());

        return response()->json(['status'  => 'success', 'role' => $role], 200);
    }

    public function deleteRole(Role $role)
    {
        $role->delete();

        return response()->json(['status'  => 'success'], 204);
    }
}
