<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedTableCCasesFieldNulleables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_cases', function (Blueprint $table) {
            $table->bigInteger('c_type_case_id')->nullable()->unsigned()->change();
            $table->bigInteger('s_client_id')->nullable()->unsigned()->change();
            $table->bigInteger('s_policy_id')->nullable()->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_cases', function (Blueprint $table) {
            $table->bigInteger('c_type_case_id')->unsigned();
            $table->bigInteger('s_client_id')->unsigned();
            $table->bigInteger('s_policy_id')->unsigned();
        });
    }
}
