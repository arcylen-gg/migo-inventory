<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_product_points extends Model
{
	protected $table = 'tbl_mlm_product_points';
	protected $primaryKey = "product_points_id";
    public $timestamps = false;
}
