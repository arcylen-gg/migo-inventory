<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_slot_points_log extends Model
{
	protected $table = 'tbl_mlm_slot_points_log';
	protected $primaryKey = "points_log_id";
    public $timestamps = false;

    public function scopeSlot($query)
    {
        return $query->leftjoin("tbl_mlm_slot","tbl_mlm_slot.slot_id","=","tbl_mlm_slot_points_log.points_log_slot");
    }

}
