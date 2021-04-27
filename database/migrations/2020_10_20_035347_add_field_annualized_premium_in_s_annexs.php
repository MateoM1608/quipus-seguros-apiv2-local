<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldAnnualizedPremiumInSAnnexs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('s_annexes', function (Blueprint $table) {
            $table->double('annualized_premium')->nullable()->after('annex_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('s_annexes', function (Blueprint $table) {
            $table->dropColumn('annualized_premium');
        });
    }
}
