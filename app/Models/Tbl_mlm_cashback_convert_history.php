<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_mlm_cashback_convert_history extends Model
{
   	protected $table = 'tbl_mlm_cashback_convert_history';
	protected $primaryKey = "cashback_convert_history_id";
    public $timestamps = false;
}

	