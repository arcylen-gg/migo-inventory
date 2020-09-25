<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_customer_invoice_pm extends Model
{
	protected $table = 'tbl_customer_invoice_pm';
	protected $primaryKey = "invoice_pm_id";
    public $timestamps = false;

    public static function scopePm($query)
    {
        return $query->leftjoin("tbl_payment_method","payment_method_id","=","inv_pm_id");
    }
}