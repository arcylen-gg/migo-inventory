<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_customer_address extends Model
{
    protected $table = 'tbl_customer_address';
	protected $primaryKey = "customer_address_id";
    public $timestamps = true;

    public function scopeCustomer($query)
    {
    	return $query->leftjoin("tbl_customer","tbl_customer.customer_id","=", "tbl_customer_address.customer_id");
    }
    public function scopeCountry($query)
    {
    	return $query->leftjoin("tbl_country","tbl_country.country_id","=", "tbl_customer_address.country_id");
    }
}
