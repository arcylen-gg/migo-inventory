<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_plan_setting extends Model
{
	protected $table = 'tbl_mlm_plan_setting';
	protected $primaryKey = "plan_settings_id";
    public $timestamps = false;
    
    //plan_settings_slot_id_format
    // 0 = auto;
    // 1 = format;
    
    //plan_settings_prefix_count 
    // 0 = prefix
    // 1 = text 
    
    //plan_settings_format 
    // text / number
    
    
    //  plan_settings_slot_id_format
    // 	->increment(default)
    // 	->random
    // 	->format
    
    //     plan_settings_format
    // 	->prefix
    // 	->text
    
    // plan_settings_prefix_count
    // sprintf('%08d', 1234567);
    // str_pad($value, 8, '0', STR_PAD_LEFT);
    // call_user_func()
}
