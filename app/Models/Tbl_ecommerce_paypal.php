<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_ecommerce_paypal extends Model
{
    protected $table = 'tbl_ecommerce_paypal';
	protected $primaryKey = "ecommerce_paypal_id";
    public $timestamps = false;
}
