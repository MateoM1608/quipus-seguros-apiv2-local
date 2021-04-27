<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('operation_name');
			$table->text('operation_description');
			$table->date('start_date');
			$table->datetime('end_date');
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('c_operation_type_id')->unsigned();
			$table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));

            //Relaciones
            $table->foreign('c_operation_type_id')->references('id')->on('c_operation_types');
            //Relacion con usuarios??
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('c_operations');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
