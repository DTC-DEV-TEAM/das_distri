<?php

use Illuminate\Database\Seeder;

class ModeOfReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mode_of_returns')->updateOrInsert(['name' => 'STORE DROP-OFF'], [
            'name' => 'STORE DROP-OFF',
            'created_by' => 3,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('mode_of_returns')->updateOrInsert(['name' => 'RMA PULLOUT'], [
            'name' => 'RMA PULLOUT',
            'created_by' => 3,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('mode_of_returns')->updateOrInsert(['name' => 'DOOR-TO-DOOR'], [
            'name' => 'DOOR-TO-DOOR',
            'created_by' => 3,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
