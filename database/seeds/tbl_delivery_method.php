<?php

use Illuminate\Database\Seeder;

class tbl_delivery_method extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('tbl_delivery_method')->truncate();
        
        $insert = [
            ['delivery_method_id'=> 1, 'delivery_method' => 'Print later', 'archived'=> 0],
            ['delivery_method_id'=> 2, 'delivery_method' => 'Send later', 'archived'=> 0],
            ['delivery_method_id'=> 3, 'delivery_method' => 'None', 'archived'=> 0]
            ];
        
        DB::table('tbl_delivery_method')->insert($insert);
    }
}
