<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('initials')->nullable();
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
        Schema::dropIfExists('g_countries');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
