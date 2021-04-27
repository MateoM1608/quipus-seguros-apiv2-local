<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateViewDashboardExpiration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("DROP VIEW IF EXISTS view_dashboard");

        \DB::statement("
        CREATE VIEW view_dashboard AS
        SELECT  'expiration' AS proccess, COUNT(*) AS DATA
        FROM(
        SELECT
        COUNT(*) AS DATA
        FROM s_policies
        INNER JOIN s_annexes ON s_annexes.s_policy_id = s_policies.id
        INNER JOIN s_clients ON s_clients.id = s_policies.s_client_id
        INNER JOIN s_branches ON s_branches.id = s_policies.s_branch_id
        INNER JOIN s_insurance_carriers ON s_insurance_carriers.id = s_branches.s_insurance_carrier_id
        WHERE (s_policies.policy_state = 'Vigente' AND s_annexes.annex_type NOT IN ('Cobro', 'Cancelación', 'Devolución')) AND DATE_FORMAT(s_annexes.annex_end, '%m') = DATE_FORMAT(NOW(),'%m') #OR DATE_FORMAT(s_annexes.annex_end, '%Y-%m') = DATE_FORMAT((DATE_ADD(NOW(), INTERVAL 1 YEAR)),'%Y-%m')
        GROUP BY policy_number) AS expiration
        UNION
        SELECT
        'balance' AS proccess, ROUND(SUM(s_annexes.annex_total_value) - COALESCE(SUM(s_payments.total_value),0)) AS DATA
        FROM
        s_annexes
        LEFT JOIN
        s_payments ON s_annexes.id = s_payments.s_annex_id
        WHERE
        (s_annexes.deleted_at IS NULL) AND s_annexes.annex_paid = 'No' UNION
        SELECT
        'commissionReceivable' AS proccess, ROUND(SUM(s_annexes.annex_commission),0) AS DATA
        FROM
        s_annexes
        LEFT JOIN
        s_payments ON s_annexes.id = s_payments.s_annex_id
        WHERE
        (s_annexes.deleted_at IS NULL) AND s_annexes.annex_paid = 'Si' AND s_annexes.commission_paid = 'No'
    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_dashboard');
    }
}
