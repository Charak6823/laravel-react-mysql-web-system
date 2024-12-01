<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Js;

use function Pest\Laravel\json;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::with('profile')->get();
        return response()->json([
            "status" => "success",
            "message" => "Retrieved data successfully!",
            "data" => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'nullable',
            'address' => 'nullable',
            'type' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        //create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        //Handle the image upload if exists
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profiles', 'public');
        }

        //create the profile
        $user->profile()->create([
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $imagePath,
            'type' => $request->type
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Data is created successfully!",
            "data" => $user->load('profile')
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('profile')->get()->find($id);
        return response()->json([
            "status" => "success",
            "message" => "Retrieve data by id successfully!",
            "data" => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Find user
        $user = User::find($id);
        if (!$user) {
            return response()->json(["status" => "error", "message" => "User not found"], 404);
        }

        // 2. Validate input
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable',
            'address' => 'nullable',
            'type' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // 3. Handle image update
        $imagePath = $user->profile->image ?? null;
        if ($imagePath && $request->hasFile('image')) {
            Storage::disk('public')->delete($imagePath);
        }
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('profiles', 'public') : $imagePath;

        // 4. Update user and profile in a transaction
        DB::transaction(function () use ($request, $user, $imagePath) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

            $user->profile()->update([
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => $imagePath,
                'type' => $request->type
            ]);
        });

        // 5. Return response
        return response()->json([
            "status" => "success",
            "message" => "Data is updated successfully!",
            "data" => $user->load('profile')
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user=User::find($id);
        return $user
            ? response()->json(["status"=>"success","message"=>"Data is deleted successfully!","data"=>$user->delete()])
            : response()->json(["status"=>"error","message"=>"Data is not found"]);
    }
}
