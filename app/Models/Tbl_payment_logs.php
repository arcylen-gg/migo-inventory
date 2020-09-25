<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tbl_payment_logs extends Model
{
   	protected $table = 'tbl_payment_logs';
	protected $primaryKey = "payment_log_id";
    public $timestamps = false;
}
