<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_item_redeemable extends Model
{
    protected $table = 'tbl_item_redeemable';
	protected $primaryKey = "item_id";
    public $timestamps = false;

}
