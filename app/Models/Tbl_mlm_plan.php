<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_plan extends Model
{
	protected $table = 'tbl_mlm_plan';
	protected $primaryKey = "marketing_plan_code_id";
    public $timestamps = false;
    // marketing_plan_enable
    // 2 = inactive
    // 1= active
    // 0 = not configure
    
    // marketing_plan_release_schedule
    // 1 = instant
    // 2 = daily
    // 3 = weekly
    // 4= monthly
    public function scopeCode($query, $code)
    {
        $query->where("tbl_mlm_plan.marketing_plan_code", $code);
    }

    public function scopeEnable($query, $enable)
    {
        $query->where("tbl_mlm_plan.marketing_plan_enable", $enable);
    }

    public function scopeTrigger($query, $trigger)
    {
        $query->where("tbl_mlm_plan.marketing_plan_trigger", $trigger);
    }
}
