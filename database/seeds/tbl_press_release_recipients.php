<?php

use Illuminate\Database\Seeder;

class tbl_press_release_recipients extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_press_release_recipients')->truncate();
        $statement = "INSERT INTO `tbl_press_release_recipients` (`recipient_id`,`media_type`) VALUES 
        	(1, 'Magazine'),
        	(2, 'Newspaper'),
            (3, 'Radio'),
            (4, 'News Agency'),
            (5, 'Periodical'),
            (6, 'Online Magazines'),
            (7, 'Blog'),
            (8, 'TV'),
            (9, 'Web / Online Services'),
            (10, 'Trade Peridicals')";
       	DB::statement($statement);
    }
}
