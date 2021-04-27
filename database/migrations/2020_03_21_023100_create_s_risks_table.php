<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_risks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('risk_number')->nullable();
            $table->string('risk_description');
            $table->double('risk_premium');
            $table->bigInteger('s_policy_id')->unsigned();
            $table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->foreign('s_policy_id')->references('id')->on('s_policies');
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
        Schema::dropIfExists('s_risks');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
