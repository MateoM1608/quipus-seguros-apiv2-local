<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCCaseStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_case_stages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->bigInteger('c_type_case_id')->unsigned();
            $table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));


            //Relaciones
            $table->foreign('c_type_case_id')->references('id')->on('c_type_cases');

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
        Schema::dropIfExists('c_case_stages');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
