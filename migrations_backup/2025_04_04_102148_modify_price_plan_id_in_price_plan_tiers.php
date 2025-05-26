<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPricePlanIdInPricePlanTiers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_plan_tiers', function (Blueprint $table) {
            $table->dropForeign(['price_plan_id']);
            $table->dropColumn('price_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_plan_tiers', function (Blueprint $table) {
            $table->foreignId('price_plan_id')->constrained()->onDelete('cascade');
        });
    }
}
