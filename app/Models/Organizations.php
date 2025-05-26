<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organizations extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'organizations';

    protected $fillable = ['id', 'name', 'contact_person', 'phone', 'organization_email', 'CIN', 'GST', 'created_at', 'updated_at', 'deleted_at', 'financial_limit', 'subscription_id', 'start_date', 'end_date'];
}