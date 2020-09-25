<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_tour_wallet_slot extends Model
{
	protected $table = 'tbl_tour_wallet_slot';
	protected $primaryKey = "id";
    public $timestamps = false;
    
    public function scopeFind($query, $tour_wallet_id, $slot_id)
    {
        return $query->where("tour_wallet_id", $tour_wallet_id)
                     ->where("slot_id", $slot_id);
    }
}