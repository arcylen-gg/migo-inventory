<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_sir_item extends Model
{
	protected $table = 'tbl_sir_item';
	protected $primaryKey = "sir_item_id";
    public $timestamps = true;

    public function scopeSelect_sir_item($query)
    {
        return $query->leftjoin("tbl_item","tbl_item.item_id","=","tbl_sir_item.item_id")
        			->leftjoin('tbl_category','tbl_category.type_id','=','tbl_item.item_category_id');
    }
    public function scopeItem($query)
    {
        return $query->leftjoin("tbl_item","tbl_item.item_id","=","tbl_sir_item.item_id");
    }
}