<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedTableCCaseNotesAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_case_notes', function (Blueprint $table) {
            $table->string('user_name')->after('user_id');
            $table->string('user_email')->after('user_name');
            $table->string('note')->after('user_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_case_notes', function (Blueprint $table) {
            $table->dropColumn('user_name');
            $table->dropColumn('user_email');
            $table->dropColumn('note');
        });
    }
}
