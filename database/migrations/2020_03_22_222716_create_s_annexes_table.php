<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSAnnexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_annexes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('annex_number');
            $table->date('annex_expedition');
            $table->date('annex_start');
            $table->date('annex_end');
            $table->double('annex_premium');
            $table->double('annex_tax');
            $table->double('annex_expedition_cost')->nullable();
            $table->double('annex_other_cost')->nullable();
            $table->double('annex_total_value');
            $table->text('annex_description')->nullable();
            $table->double('annex_commission');
            $table->enum('annex_paid', array('Si', 'No'));
            $table->enum('commission_paid', array('Si', 'No'));
            $table->enum('annex_type', array('Expedición', 'Modificación', 'Cobro', 'Cancelación', 'Renovación', 'Devolución'));
            $table->bigInteger('s_policy_id')->unsigned();
            $table->enum('annex_print', array('Si', 'No', 'N/A'));
            $table->enum('annex_printed', array('Si', 'No', 'N/A'));
            $table->enum('annex_email', array('Si', 'No', 'N/A'));
            $table->enum('annex_delivered', array('Si', 'No', 'N/A'));
            $table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));

            //Relaciones
            $table->foreign('s_policy_id')->references('id')->on('s_policies');
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
        Schema::dropIfExists('s_annexes');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
