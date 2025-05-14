<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{

    public function definition(): array
    {
        $value = $this->faker->numberBetween(1000, 1000000);
        
        return [
            'seller_id' => Seller::inRandomOrder()->first()->id,
            'value' => $value,
            'commission' => Sale::getCommissionAttribute($value),
        ];
    }
}
