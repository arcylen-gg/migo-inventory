<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_cart_payment extends Model
{
	protected $table = 'tbl_cart_payment';
	protected $primaryKey = "cart_payment_id";
    public $timestamps = false;
}