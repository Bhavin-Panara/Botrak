<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToCompanyAssetsAndCompanyPricePlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_price_plans', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
        });

        Schema::table('company_assets', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_price_plans', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });

        Schema::table('company_assets', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
