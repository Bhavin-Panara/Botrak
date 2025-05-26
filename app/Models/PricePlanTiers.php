<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricePlanTiers extends Model
{
    use HasFactory;

    protected $table = 'price_plan_tiers';

    protected $fillable = ['id', 'price_plan_id', 'start_range', 'end_range', 'price', 'created_at', 'updated_at'];

    protected $casts = [
        'start_range' => 'integer',
        'end_range' => 'integer',
        'price' => 'decimal:2'
    ];

    public function pricePlan()
    {
        return $this->belongsTo(PricePlans::class, 'price_plan_id');
    }
}