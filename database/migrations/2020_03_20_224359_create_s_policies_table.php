<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_policies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('policy_number')->unique();
            $table->date('expedition_date');
            $table->bigInteger('s_branch_id')->unsigned();
            $table->bigInteger('s_client_id')->unsigned();
            $table->bigInteger('g_vendor_id')->unsigned();
            $table->enum('policy_state', array('Vigente', 'No Renovada', 'Cancelada'));
            $table->enum('payment_periodicity', array('Anual', 'Semestral', 'Trimestral', 'Mensual', 'Pago Unico'));
            $table->bigInteger('s_agency_id')->unsigned();
            $table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('s_branch_id')->references('id')->on('s_branches');
            $table->foreign('s_client_id')->references('id')->on('s_clients');
            $table->foreign('g_vendor_id')->references('id')->on('g_vendors');
            $table->foreign('s_agency_id')->references('id')->on('s_agencies');
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
        Schema::dropIfExists('s_policies');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
