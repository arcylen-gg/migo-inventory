<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_customer_estimate_pm extends Model
{
	protected $table = 'tbl_customer_estimate_pm';
	protected $primaryKey = "estimate_pm_id";
    public $timestamps = false;

    public static function scopePm($query)
    {
        return $query->leftjoin("tbl_payment_method","payment_method_id","=","est_pm_id");
    }
}