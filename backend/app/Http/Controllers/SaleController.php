<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Http\Requests\Sale\StoreRequest;

class SaleController extends Controller
{
    public function store(StoreRequest $request)
    {
        $sale = Sale::create([
            'seller_id' => $request->sellerId(),
            'value' => $request->value() * 100,
            'date' => $request->getDate(),
        ]);

        return response()->json($sale, 201);
    }

    public function index()
    {
        return response()->json(Sale::with('seller')->get()->toArray(), 200);
    }

}
