<?php

namespace App\Http\Controllers;

use App\Models\UnitType;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unit_type_list = UnitType::all();

        return response()->json([
            "status"=>"success",
            "message"=>"Retrieve data successfully",
            "data"=>$unit_type_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=>"required|string",
            "name_kh"=>"required|string",
            "status"=>"required|boolean"
        ]);

        $unit_type = UnitType::create($request->all());

        return response()->json([
            "status"=>"success",
            "message"=>"Data is created successfully!",
            "data"=>$unit_type
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit_type = UnitType::find($id);
        if(!$unit_type){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }

        return response()->json([
            "status"=>"success",
            "message"=>"Retrieve data by id successfully!",
            "data"=>$unit_type
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $unit_type = UnitType::find($id);

        $request->validate([
            "name"=>"required|string",
            "name_kh"=>"required|string",
            "status"=>"required|boolean"
        ]);

        $unit_type->update($request->all());
        return response()->json([
            "status"=>"success",
            "message"=>"Update data successfully!",
            "data"=>$unit_type
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit_type = UnitType::find($id);
        if(!$unit_type){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }
        return response()->json([
            "status"=>"success",
            "message"=>"Data is deleted successfully!"
        ]);
    }
}
