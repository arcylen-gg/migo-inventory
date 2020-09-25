<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_variant_name extends Model
{
	protected $table = 'tbl_variant_name';
	protected $primaryKey = "variant_name_id";
    public $timestamps = false;

    public function scopeNameOnly($query)
    {
    	$query->join("tbl_option_name","tbl_option_name.option_name_id","=","tbl_variant_name.option_name_id");
    }
}
