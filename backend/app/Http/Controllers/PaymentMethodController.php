<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "status"=>"success",
            "message"=>"Data is retrieved successfully!",
            "data"=>PaymentMethod::all()
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=>"required|string",
            "code"=>"required|string|unique:payment_methods,code",
            "website"=>"nullable|string",
            "service_contact"=>"required|string",
            "status"=>"required|boolean"
        ]);
        $payment_method = PaymentMethod::create($request->all());
        return response()->json([
            "status"=>"success",
            "message"=>"Payment method is created successfully!",
            "data"=>$payment_method
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment_method = PaymentMethod::find($id);
        if (!$payment_method) {
            return response()->json([
                "message"=>"data is not found!"
            ],400);
        }
        return response()->json([
            "status"=>"success",
            "message"=>"Retrieve data by successfully!",
            "data"=>$payment_method
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment_method = PaymentMethod::find($id);
        $request->validate([
            "name"=>"required|string",
            "code"=>"required|string|unique:payment_methods,code,".$id,
            "website"=>"nullable|string",
            "service_contact"=>"required|string",
            "status"=>"required|boolean"
        ]);
        $payment_method->update($request->all());
        return response()->json([
            "status"=>"success",
            "message"=>"Payment method is updated successfully!",
            "data"=>$payment_method
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment_method = PaymentMethod::find($id);
        $payment_method->delete();
        return response()->json([
            "status"=>"success",
            "message"=>"Data is deleted successfully!"
        ]);
    }
}
