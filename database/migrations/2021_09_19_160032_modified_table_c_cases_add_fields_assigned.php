<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifiedTableCCasesAddFieldsAssigned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_cases', function (Blueprint $table) {
            $table->bigInteger('c_case_area_id')->after('c_type_case_stage_id')->unsigned();
            $table->string('creator_name')->after('creator_user_id')->nullable();
            $table->string('assigned_name')->after('assigned_user_id')->nullable();


             //Relaciones
             $table->foreign('c_case_area_id')->references('id')->on('c_case_areas');
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
            $table->dropColumn('c_case_area_id');
            $table->dropColumn('creator_name');
            $table->dropColumn('assigned_name');
        });


    }
}
