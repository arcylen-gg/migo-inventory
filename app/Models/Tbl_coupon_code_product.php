<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_coupon_code_product extends Model
{
	protected $table = 'tbl_coupon_code_product';
	protected $primaryKey = "cc_id";
    public $timestamps = false;
}