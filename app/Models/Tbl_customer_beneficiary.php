<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_customer_beneficiary extends Model
{
	protected $table = 'tbl_customer_beneficiary';
	protected $primaryKey = "beneficiary_id";
    public $timestamps = true;

    public function scopeCustomer($query)
    {
    	return $query->leftjoin("tbl_customer","tbl_customer.customer_id","=", "tbl_customer_beneficiary.customer_id");
    }
}