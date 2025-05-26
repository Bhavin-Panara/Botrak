<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPricePlans extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'company_price_plans';

    protected $fillable = ['id', 'company_id', 'price_plan_id', 'start_date', 'end_date', 'status', 'created_at', 'updated_at', 'deleted_at', 'billing_frequency'];

    // status = completed, continue, next, soon

    protected $dates = ['start_date', 'end_date'];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'company_id');
    // }

    public function organizations()
    {
        return $this->belongsTo(Organizations::class, 'company_id');
    }

    public function priceplan()
    {
        return $this->belongsTo(PricePlans::class, 'price_plan_id');
    }
}