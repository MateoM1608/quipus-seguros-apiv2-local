<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedTableSBranchesAddFieldForReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('s_branches', function (Blueprint $table) {
            $table->integer('loss_coverage')->after('s_insurance_carrier_id');
            $table->integer('cancellation_risk')->after('loss_coverage');
            $table->integer('cancellation')->after('cancellation_risk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('s_branches', function (Blueprint $table) {
            $table->dropColumn('loss_coverage');
            $table->dropColumn('cancellation_risk');
            $table->dropColumn('cancellation');
        });
    }
}
