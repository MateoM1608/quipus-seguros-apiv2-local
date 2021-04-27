<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->string('initials')->nullable();
            $table->integer('g_country_id')->unsigned()->index();
            $table->softDeletes();
			$table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));            
            
            $table->foreign('g_country_id')->references('id')->on('g_countries');
            
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
        Schema::dropIfExists('g_cities');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
