<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description', 1000);
			$table->date('start_date');
			$table->date('end_date');
			$table->double('sale_value')->nullable();
			$table->enum('open_close', array('Open', 'Close'));
			$table->bigInteger('c_process_stage_id')->unsigned();
			$table->bigInteger('s_client_id')->unsigned();
			$table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
			
			//Relaciones

			$table->foreign('c_process_stage_id')->references('id')->on('c_process_stages');
			$table->foreign('s_client_id')->references('id')->on('s_clients');
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
        Schema::dropIfExists('c_processes');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
