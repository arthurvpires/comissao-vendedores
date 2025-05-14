<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Seller;

class SaleRepository
{

    public function findOneById(int $id): Sale
    {
        return Sale::findOrFail($id);
    }

    public function getSalesBySellerAndDate(Seller $seller, Carbon $date): array
    {
        $sales = $seller->sales->whereBetween('created_at', [$date->startOfDay(), $date->endOfDay()]);

        return [
            'quantity' => $sales->count(),
            'total' => $sales->sum('value'),
            'commission' => $sales->sum('commission'),
        ];
    }

    public function getSalesByDate(Carbon $date): int
    {
        $startOfToday = $date->copy()->startOfDay();
        $endOfToday = $date->copy()->endOfDay();
        return Sale::whereBetween('created_at', [$startOfToday, $endOfToday])->sum('value');
    }
}
