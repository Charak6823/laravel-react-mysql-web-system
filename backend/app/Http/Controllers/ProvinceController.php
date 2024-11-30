<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $provinceList=Province::all();
        return response()->json([
            "message"=>"Retrieve data successfully!",
            "data"=>$provinceList
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $province = new Province();
        $province->name = $request->input('name');
        $province->code = $request->input('code');
        $province->distance_from_city = $request->input('distance_from_city');
        $province->status = $request->input('status');

        $province->save();

        return response()->json([
            "message"=>"Create data successfully!",
            "data"=>$province
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $province = Province::find($id);
        if(!$province){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }else {
            return response()->json([
                "message"=>"Retrieve data by id successfully!",
                "data"=>$province
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $province = Province::find($id);
        if(!$province){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }else{
            $province->name = $request->input('name');
            $province->code = $request->input('code');
            $province->distance_from_city = $request->input('distance_from_city');
            $province->status=$request->input('status');

            $province->update();

            return response()->json([
                "message"=>"Update data successfully!",
                "data"=>$province
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $province = Province::find($id);

        if(!$province){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }else{
            $province->delete();
            return response()->json([
                "message"=>"Delete data successfully!"
            ]);
        }
    }
}
