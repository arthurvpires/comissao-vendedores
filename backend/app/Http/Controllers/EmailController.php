<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Mail\DailySalesReportMail;
use App\Repositories\SaleRepository;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function resendDailyReportEmail(Request $request, SaleRepository $saleRepository)
    {
        $request->validate([
            'seller_id' => 'required|exists:sellers,id',
        ]);

        $seller = Seller::find($request->seller_id);
        $sales = $saleRepository->getSalesBySellerAndDate($seller, today());

        Mail::to($seller->email)->send(new DailySalesReportMail(
            $seller->name,
            $sales['quantity'],
            $sales['total'],
            $sales['commission']
        ));

        return response()->json(['message' => 'E-mail reenviado com sucesso.'], 200);
    }
}

