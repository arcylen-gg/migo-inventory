<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Rel_cashback_convert_history extends Model
{
   	protected $table = 'rel_cashback_convert_history';
	protected $primaryKey = "rel_points_log_id";
    public $timestamps = false;
}

	