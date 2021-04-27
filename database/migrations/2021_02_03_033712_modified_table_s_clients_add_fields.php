<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedTableSClientsAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('s_clients', function (Blueprint $table) {
            $table->enum('habeas_data_terms', array('Si', 'No'))->nullable()->after('observations');
            $table->enum('habeas_data_email', array('Si', 'No'))->nullable()->after('habeas_data_terms');
            $table->enum('habeas_data_sms', array('Si', 'No'))->nullable()->after('habeas_data_email');
            $table->enum('habeas_data_phone', array('Si', 'No'))->nullable()->after('habeas_data_sms');
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
            $table->dropColumn('habeas_data_terms');
            $table->dropColumn('habeas_data_email');
            $table->dropColumn('habeas_data_sms');
            $table->dropColumn('habeas_data_phone');
        });
    }
}
