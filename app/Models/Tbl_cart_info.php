<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_cart_info extends Model
{
	protected $table = 'tbl_cart_info';
	protected $primaryKey = "cart_info_id";
    public $timestamps = false;
}