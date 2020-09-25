<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_commission_item extends Model
{
	protected $table = 'tbl_commission_item';
	protected $primaryKey = "commission_item_id";
    public $timestamps = false;
}