<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetRegisters extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asset_registers';

    protected $fillable = ['id', 'name', 'date', 'created_at', 'updated_at', 'organization_id', 'deleted_at', 'plant_id'];

    public function organizationassets()
    {
        return $this->hasMany(OrganizationAssets::class, 'asset_register_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organizations::class, 'organization_id');
    }
}