<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    public function index()
    {
        $roleList = Role::all();

        return response()->json([
            "message"=>"Retrieve data successfully!",
            "Role List"=>$roleList
        ]);
    }
    public function store(Request $req)
    {
        $role = new Role();
        $role->name = $req->input('name');
        $role->description = $req->input('description');
        $role->status = $req->input('status');
        $role->save();

        return response()->json(([
            "message"=>"Create data successfully!",
            "role"=>$role
        ]));
    }
    public function show($id)
    {
        $role = Role::find($id);
        return response()->json([
            "message"=>"Retrieve data by id successfully!",
            "data"=>$role
        ]);
    }
    public function update(Request $req, $id)
    {
        $role=Role::find($id);
        if(!$role){
            return response()->json([
                "message"=>"not found"
            ]);
        }else{
            $role->name = $req->input('name');
            $role->description = $req->input('description');
            $role->status = $req->input("status");
            $role->update();
            return response()->json([
                "message"=>"Update data successfully!",
                "data:"=>$role
            ]);
        }
    }
    public function destroy($id)
    {
        $role=Role::find($id);
        if(!$role){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }else{
            $role->delete();
            return  response()->json([
                "message"=>"Data deleted successfully!"
            ]);
        }
    }
}
