<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Http\Requests\Seller\StoreRequest;

class SellerController extends Controller
{
    public function store(StoreRequest $request)
    {
        $seller = Seller::create([
            'name' => $request->name(),
            'email' => $request->email(),
        ]);

        return response()->json($seller, 201);
    }

    public function index()
    {
        return response()->json(Seller::all()->toArray(), 200);
    }

    public function salesBySeller(int $id)
    {
        $seller = Seller::findOrFail($id);
        $sales = $seller->sales;

        return response()->json($sales, 200);
    }

}
