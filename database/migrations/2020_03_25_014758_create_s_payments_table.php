<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_number')->nullable();
			$table->date('payment_date');
			$table->double('premium_value');
			$table->double('tax_value');
			$table->double('total_value');
			$table->bigInteger('s_annex_id')->unsigned();
			$table->enum('payment_form', array('Contado', 'Financiacion'));
			$table->softDeletes();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
			
			//Relaciones
			$table->foreign('s_annex_id')->references('id')->on('s_annexes');
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
        Schema::dropIfExists('s_payments');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
