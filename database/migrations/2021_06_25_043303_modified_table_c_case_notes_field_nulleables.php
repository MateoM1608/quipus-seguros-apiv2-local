<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifiedTableCCaseNotesFieldNulleables extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_case_notes', function (Blueprint $table) {
            $table->enum('type_note', array('Comentario', 'Tarea'))->after('note');
        });
        DB::statement("ALTER TABLE c_case_notes MODIFY COLUMN state ENUM('Pendiente', 'Finalizada') NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_case_notes', function (Blueprint $table) {
            $table->dropColumn('type_note');
            $table->enum('state', array('Pendiente', 'Finalizada'))->change();
        });
    }


}
