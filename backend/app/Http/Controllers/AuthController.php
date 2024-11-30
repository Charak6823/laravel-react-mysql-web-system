<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=User::with('profiles')->get();
        return response()->json([
            "status"=>"success",
            "message"=>"Retrieved data successfully!",
            "data"=>$user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
            'phone'=>'nullable',
            'address'=>'nullable',
            'type'=>'nullable',
            'image'=>'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        //create user
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        //Handle the image upload if exists
        $imagePath=null;
        if($request->hasFile('image')){
            $imagePath=$request->file('image')->store('profiles','public');
        }

        //create the profile
        $user->profile()->create([
            'phone'=>$request->phone,
            'address'=>$request->address,
            'image'=>$imagePath,
            'type'=>$request->type
        ]);

        return response()->json([
            "status"=>"success",
            "message"=>"Data is created successfully!",
            "data"=>$user->load('profile')
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //1 validate
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,'.$id,
            'phone'=>'nullable',
            'address'=>'nullable',
            'type'=>'nullable',
            'image'=>'nullable|image|mines:jpeg,png,jpg,gif|max:2048'
        ]);

        //2 find user
        $user = User::findOrFail($id);
        //3 update user
        $user->update([
            'name'=>$request->name,
            'email'=>$request->email
        ]);
        //4 handle image update
        $imagePath=null;
        if($request->hasFile('image')){
            Storage::disk('public')->delete($imagePath);
        }
        $imagePath = $request->file('image')->store('profiles','public');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
