<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addressList = Address::where('parentId', $request->parentId)->get();

        return response()->json([
            "message"=>"Retrieve data successfully!",
            "data"=>$addressList
        ]);
    }
    public function show(string $id)
    {
        $address = Address::find($id);
        if (!$address) {
            return  response()->json([
                "message"=>"Data is not found!",
            ]);
        }else{
            return  response()->json([
                "message"=>"Retrieve data successfully!",
                "data"=>$address
            ]);
        }
    }
    public function store(Request $req)
    {
        $address = new Address();
        $address->code = $req->input("code");
        $address->nameKh = $req->input("nameKh");
        $address->nameEn = $req->input("nameEn");
        $address->parentId = $req->input("parentId");

        $address->save();
        return response()->json([
            "message"=>"Create data successfully!",
            "data"=>$address
        ]);
    }
    public function update(Request $req, string $id)
    {
        $address = Address::find($id);
        if (!$address) {
            return response()->json([
                "message" => "Data is not found!",
            ]);
        } else {
            $address->code = $req->input("code");
            $address->nameKh = $req->input("nameKh");
            $address->nameEn = $req->input("nameEn");
            $address->parentId = $req->input("parentId");

            $address->update();

            return response()->json([
                "message" => "Update data successfully!",
                "data" => $address
            ]);
        }
    }

    public function destroy($id)
    {
        $address = Address::find($id);
        if(!$address){
            return response()->json([
                "message"=>"Data is not found!"
            ]);
        }else{
            $address->delete();
            return response()->json([
                "message"=>"Delete data successfully!"
            ]);
        }
    }
}
