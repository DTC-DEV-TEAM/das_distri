<?php

use Illuminate\Database\Seeder;

class CaseStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('case_status')->updateOrInsert(['case_status_name' => 'Open'],
            [
                'case_status_name' => 'Open',
                'created_by' => 776,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );    
        DB::table('case_status')->updateOrInsert(['case_status_name' => 'Pending Customer'],
            [
                'case_status_name' => 'Pending Customer',
                'created_by' => 776,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );    
        DB::table('case_status')->updateOrInsert(['case_status_name' => 'Pending Supplier'],
            [
                'case_status_name' => 'Pending Supplier',
                'created_by' => 776,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );    
        DB::table('case_status')->updateOrInsert(['case_status_name' => 'Closed'],
            [
                'case_status_name' => 'Closed',
                'created_by' => 776,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );    
    }
}
