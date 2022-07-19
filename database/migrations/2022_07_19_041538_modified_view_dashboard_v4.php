<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifiedViewDashboardV4 extends Migration
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
        SELECT
        'reports' AS clasification,
        'Vencimientos' AS label,
        'NUMBER' AS type,
        'reports/expiration' AS url,
        '' AS icon,
        COUNT(*) AS value
        FROM(
        SELECT COUNT(*) AS value
        FROM s_policies
        INNER JOIN s_annexes ON s_annexes.s_policy_id = s_policies.id
        INNER JOIN s_clients ON s_clients.id = s_policies.s_client_id
        INNER JOIN s_branches ON s_branches.id = s_policies.s_branch_id
        INNER JOIN s_insurance_carriers ON s_insurance_carriers.id = s_branches.s_insurance_carrier_id
        WHERE (s_policies.policy_state = 'Vigente' AND s_annexes.annex_type NOT IN ('Cobro', 'Cancelación', 'Devolución')) AND DATE_FORMAT(s_annexes.annex_end, '%m') = DATE_FORMAT(NOW(),'%m') #OR DATE_FORMAT(s_annexes.annex_end, '%Y-%m') = DATE_FORMAT((DATE_ADD(NOW(), INTERVAL 1 YEAR)),'%Y-%m')
        GROUP BY policy_number) AS Vencimientos

        UNION

        SELECT
        'reports' AS clasification,
        'Cartera' AS label,
        'AMOUNT' AS type,
        'reports/portfolio' AS url,
        '' AS icon,
        ROUND(SUM(s_annexes.annex_total_value) - COALESCE(SUM(s_payments.total_value),0)) AS value
        FROM
        s_annexes
        LEFT JOIN
        s_payments ON s_annexes.id = s_payments.s_annex_id
        WHERE
        (s_annexes.deleted_at IS NULL) AND s_annexes.annex_paid = 'No'


        UNION

        SELECT
        'reports' AS clasification,
        'Comisiones por cobrar' AS label,
        'AMOUNT' AS type,
        'reports/commissionReceivable' AS url,
        '' AS icon,
        ROUND(SUM(s_annexes.annex_commission),0) AS value
        FROM
        s_annexes
        LEFT JOIN
        s_payments ON s_annexes.id = s_payments.s_annex_id
        WHERE
        (s_annexes.deleted_at IS NULL) AND s_annexes.annex_paid = 'Si' AND s_annexes.commission_paid = 'No'

        UNION

        SELECT
        'reports' AS clasification,
        'Comisiones por pagar' AS label,
        'AMOUNT' AS type,
        'reports/vendorCommission' AS url,
        '' AS icon,
        ROUND(SUM(s_commissions.commission_value * (g_vendors.commission/100)),0) AS value

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

        UNION

        SELECT
        'indicators' AS clasification,
        'Producción total' AS label,
        'AMOUNT' AS type,
        '' AS url,
        'far fa-money-bill-alt' AS icon,
        COALESCE(ROUND(SUM(s_annexes.annualized_premium)), 0) AS `value`
        FROM
            s_annexes
        WHERE DATE_FORMAT(s_annexes.annex_start, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m') AND s_annexes.deleted_at IS NULL

        UNION

        SELECT
        'indicators' AS clasification,
        'Producción nueva' AS label,
        'AMOUNT' AS type,
        '' AS url,
        'fas fa-funnel-dollar' AS icon,
        COALESCE(ROUND(SUM(s_annexes.annualized_premium)), 0) AS `value`
        FROM
            s_annexes
        WHERE annex_type = 'Expedición'
        AND DATE_FORMAT(s_annexes.annex_start, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m') AND s_annexes.deleted_at IS NULL

        UNION

        SELECT
        'indicators' AS clasification,
        'Producción + Comisiones' AS label,
        'AMOUNT' AS type,
        '' AS url,
        'fas fa-money-check-alt' AS icon,
        '0' AS value
        FROM
        s_annexes
        WHERE s_annexes.id = 1

        UNION

        SELECT
        'indicators' AS clasification,
        'Incremento Producción' AS label,
        'PERCENTAGE' AS type,
        '' AS url,
        'fas fa-chart-line' AS icon,
        ROUND(((`current` - `last`) / `last`) * 100,0) AS value
        FROM (
        SELECT (
        SELECT COALESCE(ROUND(SUM(s_annexes.annualized_premium)),0)
        FROM s_annexes
        WHERE DATE_FORMAT(s_annexes.annex_start, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')) AS `current`,
        (
        SELECT COALESCE(ROUND(SUM(s_annexes.annualized_premium)),0)
        FROM s_annexes
        WHERE DATE_FORMAT(s_annexes.annex_start, '%Y-%m') = DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 YEAR), '%Y-%m')) AS `last`) AS perce

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
