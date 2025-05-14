<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'seller_id',
        'value',
        'commission',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($sale) {
            $sale->commission = self::getCommissionAttribute($sale->value);
        });
    }

    public static function getCommissionAttribute(int $value): int
    {
        return round($value * 0.085);
    }

}
