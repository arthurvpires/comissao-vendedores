<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email'
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
