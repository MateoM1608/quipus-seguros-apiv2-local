<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCCaseNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_case_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('c_case_id')->unsigned();
            $table->bigInteger('user_id'); //Pendiente revisar lo de las relaciones
            $table->date('end_date')->nullable();
            $table->enum('state', array('Pendiente', 'Finalizada'));
			$table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));


            //Relaciones
            $table->foreign('c_case_id')->references('id')->on('c_cases');
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
        Schema::dropIfExists('c_case_notes');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
