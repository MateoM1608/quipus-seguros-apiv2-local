<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedTableCCasesAddFieldStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_cases', function (Blueprint $table) {
            $table->double('projected_value')->nullable()->after('assigned_name');
            $table->double('real_value')->nullable()->after('projected_value');
            $table->enum('status_case', array('Abierto','Cerrado'))->nullable()->after('calification');
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
            $table->dropColumn('projected_value');
            $table->dropColumn('real_value');
            $table->dropColumn('status_case');
        });
    }
}
