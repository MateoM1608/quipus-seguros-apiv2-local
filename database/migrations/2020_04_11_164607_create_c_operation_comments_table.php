<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCOperationCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_operation_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('comment_description');
			$table->datetime('comment_date');
			$table->bigInteger('user_id')->unsigned();
			$table->bigInteger('c_operation_id')->unsigned();
			$table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));

			//Relaciones

			$table->foreign('c_operation_id')->references('id')->on('c_operations');
			//Relacion con usuarios??
			//$table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('c_operation_comments');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
