<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_item_redeemable_request extends Model
{
    protected $table = 'tbl_item_redeemable_request';
	protected $primaryKey = "redeemable_request_id";
    public $timestamps = false;

}
