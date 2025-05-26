<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versions extends Model
{
    use HasFactory;

    protected $table = 'versions';

    protected $fillable = ['id', 'item_type', 'item_id', 'event', 'whodunnit', 'object', 'created_at', 'object_changes', 'reason', 'employee_id'];
}