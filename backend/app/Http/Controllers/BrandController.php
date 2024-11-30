<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Brand;
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
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:brands,code',
            'from_country' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,disable'
        ]);

        $imagePath = null;

        // Check if an image file is present in the request
        if ($request->hasFile('image')) {
            try {
                // Attempt to store the file in the 'brands' directory under the 'public' disk
                $imagePath = $request->file('image')->store('brands', 'public');
            } catch (\Exception $e) {
                // Log the error if the file upload fails
                Log::error('File upload error: ' . $e->getMessage());

                // Return a JSON response indicating failure
                return response()->json([
                    "status" => "error",
                    "message" => "File upload failed!"
                ], 500);
            }
        }

        // Create a new Brand record in the database
        $brand = Brand::create([
            'name' => $request->name,
            'code' => $request->code,
            'from_country' => $request->from_country,
            'status' => $request->status,
            'image' => $imagePath
        ]);

        // Return a success response with the created data
        return response()->json([
            "status" => "success",
            "message" => "Data is created successfully!",
            "data" => $brand
        ], 201);
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        return response()->json([
            "status"=>$brand? "success":"error",
            "message"=>$brand? "Data is deleted successfully!":"Data is not found!",
            "data"=>$brand?$brand->delete():null
        ],$brand ? 200 : 400);
    }
}
