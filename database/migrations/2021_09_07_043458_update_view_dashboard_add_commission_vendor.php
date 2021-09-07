<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateViewDashboardAddCommissionVendor extends Migration
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
        SELECT  'expiration' AS proccess, COUNT(*) AS data
        FROM(
        SELECT
        COUNT(*) AS data
        FROM s_policies
        INNER JOIN s_annexes ON s_annexes.s_policy_id = s_policies.id
        INNER JOIN s_clients ON s_clients.id = s_policies.s_client_id
        INNER JOIN s_branches ON s_branches.id = s_policies.s_branch_id
        INNER JOIN s_insurance_carriers ON s_insurance_carriers.id = s_branches.s_insurance_carrier_id
        WHERE (s_policies.policy_state = 'Vigente' AND s_annexes.annex_type NOT IN ('Cobro', 'Cancelación', 'Devolución')) AND DATE_FORMAT(s_annexes.annex_end, '%m') = DATE_FORMAT(NOW(),'%m') #OR DATE_FORMAT(s_annexes.annex_end, '%Y-%m') = DATE_FORMAT((DATE_ADD(NOW(), INTERVAL 1 YEAR)),'%Y-%m')
        GROUP BY policy_number) AS expiration
        UNION
        SELECT
        'balance' AS proccess, ROUND(SUM(s_annexes.annex_total_value) - COALESCE(SUM(s_payments.total_value),0)) AS data
        FROM
        s_annexes
        LEFT JOIN
        s_payments ON s_annexes.id = s_payments.s_annex_id
        WHERE
        (s_annexes.deleted_at IS NULL) AND s_annexes.annex_paid = 'No' UNION
        SELECT
        'commissionReceivable' AS proccess, ROUND(SUM(s_annexes.annex_commission),0) AS data
        FROM
        s_annexes
        LEFT JOIN
        s_payments ON s_annexes.id = s_payments.s_annex_id
        WHERE
        (s_annexes.deleted_at IS NULL) AND s_annexes.annex_paid = 'Si' AND s_annexes.commission_paid = 'No'
        UNION
        SELECT
        'vendorCommission' AS PROCESS, ROUND(SUM(s_commissions.commission_value),0) AS DATA
        FROM s_policies
        INNER JOIN s_branches ON s_branches.id = s_policies.s_branch_id
        INNER JOIN s_clients ON s_clients.id = s_policies.s_client_id
        INNER JOIN g_vendors ON g_vendors.id = s_policies.g_vendor_id
        INNER JOIN s_agencies ON s_agencies.id = s_policies.s_agency_id
        INNER JOIN s_annexes ON s_annexes.s_policy_id = s_policies.id
        INNER JOIN s_commissions ON s_commissions.s_annex_id = s_annexes.id
        WHERE s_commissions.deleted_at IS NULL
        AND s_annexes.commission_paid = 'Si'
        AND s_commissions.vendor_commission_paid = 'No'
        AND s_commissions.commission_value > 0
    ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
