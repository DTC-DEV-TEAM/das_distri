<?php

use Illuminate\Database\Seeder;

class ReferenceCounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reference_counters')->updateOrInsert(['name' => 'INC'],
            [
                'name' => 'INC',
                'created_by' => 3,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );
    }
}
