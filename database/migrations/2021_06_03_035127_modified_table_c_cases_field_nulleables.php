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
            $table->bigInteger('s_client_id')->nullable()->unsigned()->change();
            $table->bigInteger('s_policy_id')->nullable()->unsigned()->change();
            $table->bigInteger('calification')->nullable()->change();
            $table->bigInteger('creator_user_id')->after('risk');
            $table->bigInteger('assigned_user_id')->after('creator_user_id');
            $table->date('expiration_date')->nullable()->after('assigned_user_id');

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
            $table->bigInteger('s_client_id')->unsigned()->change();
            $table->bigInteger('s_policy_id')->unsigned()->change();
            $table->bigInteger('calification')->unsigned()->change();
            $table->dropColumn('creator_user_id');
            $table->dropColumn('assigned_user_id');
            $table->dropColumn('expitaion_date');

        });
    }
}
