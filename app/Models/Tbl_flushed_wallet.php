<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_flushed_wallet extends Model
{
   	protected $table = 'tbl_flushed_wallet';
	protected $primaryKey = "flushed_id";
    public $timestamps = false;
}

	