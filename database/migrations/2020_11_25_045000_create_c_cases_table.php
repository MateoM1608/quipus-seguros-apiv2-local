<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_cases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('c_type_case_id')->unsigned();
            $table->bigInteger('s_client_id')->unsigned();
            $table->bigInteger('s_policy_id')->unsigned();
            $table->bigInteger('c_type_case_stage_id')->unsigned();
            $table->string('risk')->nullable();
            $table->string('description')->nullable();
            $table->integer('calification');
			$table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));


             //Relaciones
             $table->foreign('c_type_case_id')->references('id')->on('c_type_cases');
             $table->foreign('s_client_id')->references('id')->on('s_clients');
             $table->foreign('s_policy_id')->references('id')->on('s_policies');
             $table->foreign('c_type_case_stage_id')->references('id')->on('c_case_stages');
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
        Schema::dropIfExists('c_cases');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
