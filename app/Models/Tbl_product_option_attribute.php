<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_product_option extends Model
{
	protected $table = 'tbl_product_option_attribute';
	protected $primaryKey = "attribute_id";
    public $timestamps = false;
}