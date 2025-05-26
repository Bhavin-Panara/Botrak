<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricePlans extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'price_plans';

    protected $fillable = ['id', 'name', 'plan_type', 'is_unlimited', 'unlimited_price', 'per_asset_price', 'total_days', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_unlimited' => 'boolean',
        'unlimited_price' => 'decimal:2',
        'per_asset_price' => 'decimal:2'
    ];

    public function tiers()
    {
        return $this->hasMany(PricePlanTiers::class, 'price_plan_id');
    }

    // public function companies()
    // {
    //     return $this->hasMany(CompanyPricePlans::class);
    // }
}