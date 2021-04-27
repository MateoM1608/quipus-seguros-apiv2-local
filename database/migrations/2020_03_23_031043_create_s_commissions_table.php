<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_commissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('commission_number')->nullable();
            $table->date('commission_date');
            $table->double('commission_value');
            $table->bigInteger('s_annex_id')->unsigned();
            $table->bigInteger('s_payroll_id')->unsigned();
            $table->bigInteger('g_vendor_id')->unsigned();
            $table->enum('vendor_commission_paid', array('Si', 'No'));
            $table->double('agency_commission');
            $table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));

            //relaciones
            $table->foreign('s_annex_id')->references('id')->on('s_annexes');
            $table->foreign('g_vendor_id')->references('id')->on('g_vendors');
            //Tabla pendiente
            //$table->foreign('s_payroll_id')->references('id')->on('s_payrolls');

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
        Schema::dropIfExists('s_commissions');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
