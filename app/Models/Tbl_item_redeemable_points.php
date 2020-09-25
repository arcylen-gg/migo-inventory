<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_item_redeemable_points extends Model
{
    protected $table = 'tbl_item_redeemable_points';
	protected $primaryKey = "redeemable_points_id";
    public $timestamps = false;

}
