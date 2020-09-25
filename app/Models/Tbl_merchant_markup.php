<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_merchant_markup extends Model
{
	protected $table = 'tbl_merchant_markup';
	protected $primaryKey = "merchant_markup_id";
    public $timestamps = false;
}