<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            "status"=>"success",
            "message"=>"Retrieved data successfully!",
            "data"=>$categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'code'=>'required|string|unique:categories,code',
            'parent_id'=>'nullable|exists:categories,id',
            'image'=>'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'status'=>'required|boolean'
        ]);

        $imgPath = $request->file('image') ? $request->file('image')->store('categories','public'): null;

        $category = Category::create([
            'name'=>$request->name,
            'code'=>$request->code,
            'parent_id'=>$request->parent_id,
            'image'=>$imgPath,
            'status'=>$request->status
        ]);

        return response()->json([
            "status"=>"success",
            "message"=>"Data is created successfully!",
            "data"=>$category
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category=Category::findOrFail($id);
        if(!$category){
            return response()->json([
                "status"=>"error",
                "message"=>"Data is not found!",
                "Data"=>null
            ]);
        }else {
            return response()->json([
                "status"=>"success",
                "message"=>"Retrieved data by id successfully!",
                "data"=>$category
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $category=Category::findOrFail($id);
            $request->validate([
                'name'=>'required|string',
                'code'=>'required|string|unique:categories,code,'.$id,
                'parent_id'=>'nullable|exists:categories,id',
                'image'=>'nullable|image|mimes:jpeg,jpg,png,gif|max:2048'
            ]);
            if($request->hasFile('image')){
                if($category->image){
                    Storage::disk('public')->delete($category->image);
                }
                $category->image = $request->file('image')->store('categories','public');
            }
            $category->update([
                'name'=>$request->name,
                'code'=>$request->code,
                'parent_id'=>$request->parent_id,
                'status'=>$request->status
            ]);
            return response()->json([
                "status"=>"success",
                "message"=>"Data is updated successfully!",
                "data"=>$category
            ]);

        }catch(ModelNotFoundException $e){
            return response()->json([
                "status"=>"error",
                "message"=>"Data is not found!"
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status"=>"error",
                "message"=>"An unexpected error occurred!"
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $category=Category::findOrFail($id);
            if($category->image) Storage::disk('public')->delete($category->image);
            $category->delete();

            return response()->json([
                "status"=>"success",
                "message"=>"Data is deleted successfully!",
            ],200);
        }catch(ModelNotFoundException $e){
            return response()->json([
                "status"=>"error",
                "message"=>"Data is not found!",
            ],400);
        }catch(\Exception $e){
            return response()->json([
                "status"=>"error",
                "message"=>"An unexpected error occurred!"
            ],500);
        }
    }
}
