<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Permission\CreatePermission;
use App\Http\Requests\Permission\UpdatePermission;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index() 
    {
        $permissions = Permission::all();
        return response()->json(["status" => "success", "permissions" => $permissions],200);
    }

    public function show($id) {
        $permission = Permission::findOrFail($id);
        return response()->json($permission,200);
    }

    public function store(CreatePermission $request) 
    {
        $permission = Permission::create([
            'name' => $request->name,
            'descriptions' => $request->description
        ]);
        return response()->json(["status" => "success", "created_permissions" => $permission],200);
    }

    public function update(UpdatePermission $request) 
    {
        $permission = Permission::find($request->id)->update([
            'name' => $request->name,
            'descriptions' => $request->description
        ]);
        return response()->json(["status" => "success", "updated_permissions" => $permission],200);
    }

    public function delete($id) 
    {
        $permission = Permission::find($id)->delete();
        return response()->json(["status" => "success", "deleted_permissions" => $permission],200);
    }
}
