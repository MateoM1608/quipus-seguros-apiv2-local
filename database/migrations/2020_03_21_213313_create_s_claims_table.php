<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_claims', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('claim_number')->nullable();
			$table->date('claim_date');
			$table->date('notice_date');
			$table->double('claim_value')->nullable();
			$table->double('paid_value')->nullable();
			$table->date('payment_date')->nullable();
			$table->date('objection_date')->nullable();
			$table->text('claim_description')->nullable();                                        
			$table->bigInteger('s_policy_id')->unsigned();
			$table->enum('claim_status', array('Recibido del Cliente', 'Presentado a Aseguradora', 'Documentación Completa', 'Pagado', 'Objetado'));
			$table->softDeletes();
			$table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('s_claims');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
