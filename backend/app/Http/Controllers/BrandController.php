<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Brand;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brand = Brand::all();
        return response()->json([
            "status"=>"success",
            "message"=>"Data is retrieve successfully!",
            "data"=>$brand
        ]);
    }

    /**
     * Store a newly created resource in storage.l
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string',
                'code' => 'required|string|unique:brands,code',
                'from_country' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status' => 'required|in:active,disable'
            ]);
            if($request->hasFile('image')){
                $imagePath=$request->file('image') ? $request->file('image')->store('brands','public'):null;
            }
            $brand=Brand::create([
                'name'=>$request->name,
                'code'=>$request->code,
                'from_country'=>$request->from_country,
                'image'=>$imagePath,
                'status'=>$request->status,
            ]);
            return response()->json([
                "status"=>"success",
                "message"=>"Data is created successfully!",
                "data"=>$brand
            ],200);
        }catch(\Exception $e){
            return response()->json([
                "status"=>"error",
                "message"=>"An unexpected error occurred! ".$e
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::find($id);
        return response()->json([
            "status"=>$brand? "success":"400",
            "message"=>$brand? "Retrieved data by id successfully!":"Data is not found!",
            "data"=>$brand
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $brand = Brand::find($id);
            $request->validate([
                'name'=>'required|string',
                'code'=>'required|string|unique:brands,code,'.$id,
                'from_country'=>'required|string',
                'image'=>'nullable|image|mimes:jpeg,jpj,png,gif|max:2048',
                'status'=>'required|in:active,inactive'
            ]);

            if($request->hasFile('image')){
                if($brand->image){
                    Storage::disk('public')->delete($brand->image);
                }
            }
            $imagePath=$request->file('image')->store('brands','public');
            $brand->image=$imagePath;

            $brand->update([
                'name'=>$request->name,
                'code'=>$request->code,
                'from_country'=>$request->from_country,
                'status'=>$request->status
            ]);

            return response()->json([
                "status"=>"success",
                "message"=>"Data is updated successfully!",
                "data"=>$brand
            ],200);
        }catch(ModelNotFoundException $e){
            return response()->json([
                "status"=>"error",
                "message"=>"Data is not found! ".$e
            ]);
        }catch(Exception $e){
            return response()->json([
                "status"=>"error",
                "message"=>"An unexpected error occurred! ".$e
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $brand = Brand::findOrFail($id);
            if($brand->image){
                Storage::disk('public')->delete($brand->image);
            }
            $brand->delete();
            return response()->json([
                "status"=>"success",
                "message"=>"data is deleted successfully!"
            ]);
        }catch(ModelNotFoundException $e){
            return response()->json([
                "status"=>"error",
                "message"=>"data is not found! ".$e
            ],400);
        }catch(\Exception $e){
            return response()->json([
                "status"=>"error",
                "message"=>"An unexpected error occurred! ".$e
            ]);
        }
    }
}
