<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->double('commission');
            $table->double('tax');
            $table->bigInteger('s_insurance_carrier_id')->unsigned();
            $table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('s_insurance_carrier_id')->references('id')->on('s_insurance_carriers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('s_branches');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
