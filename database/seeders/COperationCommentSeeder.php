<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class COperationCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $operationComment = factory(App\Models\Crm\COperationComment::class, 20)->create();
    }
}
