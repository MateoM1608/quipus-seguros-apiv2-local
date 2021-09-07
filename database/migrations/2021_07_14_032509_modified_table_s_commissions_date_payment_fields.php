<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedTableSCommissionsDatePaymentFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('s_commissions', function (Blueprint $table) {
            $table->date('payment_day')->nullable()->after('vendor_commission_paid');
            $table->enum('status_payment', array('En revision','Por pagar','Pagado'))->nullable()->after('payment_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('s_commissions', function (Blueprint $table) {
            $table->dropColumn('payment_day');
            $table->dropColumn('status_payment');
        });
    }
}
