<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_item_discount extends Model
{
	protected $table = 'tbl_item_discount';
	protected $primaryKey = "item_discount_id";
    public $timestamps = true;
}