<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_ec_variant_image extends Model
{
	protected $table = 'tbl_ec_variant_image';
	protected $primaryKey = "eimage_id";
    public $timestamps = false;

    public function scopePath($query)
    {
    	return $query->join("tbl_image","image_id","=","eimg_image_id");
    }
}