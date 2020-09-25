<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_item_price_history extends Model
{
	protected $table = 'tbl_item_price_history';
	protected $primaryKey = "item_price_history_id";
    public $timestamps = false;
}