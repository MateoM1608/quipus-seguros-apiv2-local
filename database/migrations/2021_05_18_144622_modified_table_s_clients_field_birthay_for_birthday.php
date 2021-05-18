<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedTableSClientsFieldBirthayForBirthday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('s_clients', function (Blueprint $table) {
            $table->renameColumn('birthay','birthday');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('s_clients', function (Blueprint $table) {
            $table->renameColumn('birthday','birthay');
        });
    }
}
