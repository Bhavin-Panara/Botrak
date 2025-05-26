<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationAssets extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'organization_assets';

    protected $fillable = ['id', 'asset_code', 'asset_type', 'supplier_name', 'invoice_date', 'installation_date', 'created_at', 'updated_at', 'asset_register_id', 'qr_code', 'asset_description', 'deleted_at', 'location_id', 'checkin_checkout', 'condition', 'usage', 'delete_reason', 'remarks', 'amount', 'life_of_asset', 'depreciation_per_year', 'scrap_percentage', 'scrap_date_of_asset', 'selling_price', 'selling_price_currency', 'selling_date', 'deprecated', 'opening_wdv_year', 'opening_wdv_amount', 'opening_income_tax_wdv_amount', 'opening_income_tax_wdv_year', 'income_tax_rate', 'status', 'sub_location', 'machine_code', 'bussiness_area_code'];
}