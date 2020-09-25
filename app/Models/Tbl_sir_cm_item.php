<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_sir_cm_item extends Model
{
  	protected $table = 'tbl_sir_cm_item';
	protected $primaryKey = "s_cm_item_id";
    public $timestamps = true;   

    public function scopeItem($query)
    {
    	return $query->leftjoin("tbl_item","sc_item_id","=","item_id");
    }

}
