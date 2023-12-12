<?php

use Illuminate\Database\Seeder;

class WarrantyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('warranty_statuses')->updateOrInsert(['warranty_status' => 'RMA RECEIVED'],
            [
                'warranty_status' => 'RMA RECEIVED',
                'created_by' => 3,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );

        DB::table('warranty_statuses')->updateOrInsert(['warranty_status' => 'FOR ACTION'],
            [
                'warranty_status' => 'FOR ACTION',
                'created_by' => 3,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );
    }
}
