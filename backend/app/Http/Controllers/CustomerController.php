<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = new Customer();
        return response()->json([
            "status"=>"success",
            "message"=>"Retrieve data successfully!",
            "data"=>$customer->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "first_name"=>"required|string",
            "last_name"=>"required|string",
            "gender"=>"required|boolean",
            "tel"=>"required|string|unique:customers",
            "email"=>"nullable|email|unique:customers",
            "dob"=>"nullable|date"
        ]);
        $customer = Customer::create($request->all());
        return response()->json([
            "status"=>"success",
            "message"=>"Data is created successfully!",
            "data"=>$customer
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            "status"=>"success",
            "message"=>"Retrieve data by id successfully!",
            "data"=>Customer::find($id)
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::find($id);
        $request->validate([
            "first_name"=>"required|string",
            "last_name"=>"required|string",
            "gender"=>"required|boolean",
            "tel"=>"required|string|unique:customers,tel,".$id,
            "email"=>"nullable|email|unique:customers,email,",$id,
            "dob"=>"nullable|date"
        ]);
        $customer->update($request->all());
        return response()->json([
            "status"=>"success",
            "message"=>"Data is updated successfully!",
            "data"=>$customer
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer=Customer::find($id);
        return response()->json([
            "status"=>"success",
            "message"=>"Data is deleted successfully!",
            $customer->delete()
        ],200);
    }
}
