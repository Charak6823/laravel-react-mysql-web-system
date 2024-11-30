<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee_list = Employee::all();
        return response()->json([
            "message"=>"Retrieve data successfully!",
            "data"=>$employee_list
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'dob' => 'required|date',
                'gender' => 'required|in:male,female,other',
                'tel' => 'required|string|min:10|max:15',
                'email' => 'required|email',
                'salary' => 'required|numeric|min:0',
                'address_info' => 'required|string|max:500',
                'card_id' => 'required|string|unique:employees,card_id',
                'province_id' => 'required|exists:provinces,id',
                'status' => 'required|boolean'
            ]);

            $employee = Employee::create($validatedData);

            return response()->json([
                "message" => "Create data successfully!",
                "data" => $employee
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "message" => "Validation failed",
                "errors" => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::find($id);

        if(!$employee){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }

        return response()->json([
            "message"=>"Retrieve data by id successfully!",
            "data"=>$employee
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $employee = Employee::find($id);
        if(!$employee){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }

        $employee->name = $request->input('name');
        $employee->dob = $request->input('dob');
        $employee->gender = $request->input('gender');
        $employee->tel = $request->input('tel');
        $employee->email = $request->input('email');
        $employee->salary = $request->input('salary');
        $employee->address_info = $request->input('address_info');
        $employee->province_id = $request->input('province_id');
        $employee->card_id = $request->input('card_id');
        $employee->status = $request->input('status');
        $employee->update();
        return response()->json([
            "message"=>"Data is updated successfully!",
            "data"=>$employee
        ]);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);

        if(!$employee){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }

        $employee->delete();
        return response()->json([
            "message"=>"Data is deleted successfully!"
        ]);
    }
}
