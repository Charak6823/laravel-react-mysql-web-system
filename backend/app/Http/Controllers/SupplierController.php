<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();
        if($request->has('id')){
            $query->where('id', $request->input('id'));
        }
        if($request->has('name')){
            $query->where('name', 'like', '%'. $request->input('name').'%');
        }
        if($request->has('code')){
            $query->where('code', $request->input('code'));
        }
        if($request->has('email')){
            $query->where('email', $request->input('email'));
        }
        if($request->has('status')){
            $query->where('status', $request->input('status'));
        }
        return response()->json([
            "status"=>"success",
            "message"=>"Retrieve data successfully",
            "data"=>$query->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=>"required|string",
            "code"=>"required|string|unique:suppliers",
            "tel_contact"=>"required|unique:suppliers",
            "email"=>"nullable|email|unique:suppliers",
            "address"=>"nullable|string",
            "website"=>"nullable|url",
            "status"=>"required|boolean"
        ]);
        $supplier = Supplier::create($request->all());

        return response()->json([
            "message"=>"Data is created successfully!",
            "data"=>$supplier
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            "message"=>"Retrieve data by id successfully!",
            "data"=>Supplier::find($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);
        if(!$supplier){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }

        $request->validate([
            "name"=>"required|string",
            "code"=>"required|string|unique:suppliers,code,".$id,
            "tel_contact"=>"required|required|unique:suppliers,tel_contact,".$id,
            "email"=>"nullable|email|unique:suppliers,email,".$id,
            "address"=>"nullable|string",
            "website"=>"nullable|url",
            "status"=>"required|boolean"
        ]);

        $supplier->update($request->all());
        return response()->json([
            "message"=>"Data is updated successfully!",
            "data"=>$supplier
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::find($id);

        if(!$supplier){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }

        $supplier->delete();
        return response()->json([
            "Data is deleted successfully!"
        ]);
    }
}
