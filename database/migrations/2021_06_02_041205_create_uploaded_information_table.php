<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadedInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploaded_information', function (Blueprint $table) {
            $table->id();
            $table->string('nit');
            $table->string('type');
            $table->string('path');
            $table->string('log')->nullable();
            $table->string('total_records');
            $table->string('inserted_registry')->nullable();
            $table->string('bad_records')->nullable();
            $table->bigInteger('user_id')->unsigned();
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
        Schema::dropIfExists('uploaded_information');
    }
}
