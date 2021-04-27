<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGIdentificationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_identification_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('alias')->nullable();
            $table->softDeletes();
			$table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            
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
        Schema::dropIfExists('g_identification_types');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
