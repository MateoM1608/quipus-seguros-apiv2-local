<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UserSeeder::class);
        //$this->call(ModuleSeeder::class);
        //$this->call(PermissionSeeder::class);
        $this->call(GCountrySeeder::class);
        $this->call(GCitySeeder::class);
        $this->call(GIdentificationTypeSeeder::class);
        $this->call(SAgencySeeder::class);
        $this->call(GVendorSeeder::class);
        $this->call(SInsuranceCarrierSeeder::class);
        $this->call(SBranchSeeder::class);
        $this->call(SClientSeeder::class);
        $this->call(SPolicySeeder::class);
        $this->call(SRiskSeeder::class);
        $this->call(SClaimSeeder::class);
        $this->call(SAnnexSeeder::class);
        $this->call(SPaymentSeeder::class);
        $this->call(SCommissionSeeder::class);
        $this->call(CProcessStageSeeder::class);
        $this->call(CProcessSeeder::class);
        $this->call(COperationTypeSeeder::class);
        $this->call(COperationSeeder::class);
        $this->call(COperationCommentSeeder::class);
        $this->call(CCaseStageSeeder::class);
        $this->call(CTypeCaseSeeder::class);
        $this->call(CProcessStageSeeder::class);
    }
}
