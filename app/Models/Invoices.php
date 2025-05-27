<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoices extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoices';

    protected $fillable = ['id', 'invoice_number', 'generate_date', 'sent_date', 'invoice_status', 'invoice_sender_id', 'invoice_receiver_id', 'company_price_plans_id', 'plan_start_date', 'plan_end_date', 'amount', 'discount', 'sgst', 'cgst', 'tax_total', 'total_amount', 'payment_status', 'created_at', 'updated_at', 'deleted_at'];

    // invoice_status = generated, sent
    // payment_status = pending, paid, failed

    public function sender()
    {
        return $this->belongsTo(User::class, 'invoice_sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Organizations::class, 'invoice_receiver_id');
    }

    public function companypriceplans()
    {
        return $this->belongsTo(CompanyPricePlans::class, 'company_price_plans_id');
    }
}