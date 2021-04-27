<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identification')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birthay');
            $table->string('cellphone');
            $table->string('email');
            $table->double('commission');
            $table->integer('g_identification_type_id')->unsigned()->index();
            $table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('g_identification_type_id')->references('id')->on('g_identification_types');
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
        Schema::dropIfExists('g_vendors');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
