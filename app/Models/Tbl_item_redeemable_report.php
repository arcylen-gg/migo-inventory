<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_item_redeemable_report extends Model
{
    protected $table = 'tbl_item_redeemable_report';
	protected $primaryKey = "redeemable_report_id";
    public $timestamps = false;

    public function scopeSlot($query)
    {
    	$query->join('tbl_mlm_slot','tbl_mlm_slot.slot_id','=','tbl_item_redeemable_report.slot_id');
    }

}
