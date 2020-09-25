<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_merchant_commission_report_setting extends Model
{
   	protected $table = 'tbl_merchant_commission_report_setting';
	protected $primaryKey = "merchant_report_id";
    public $timestamps = false;

}
