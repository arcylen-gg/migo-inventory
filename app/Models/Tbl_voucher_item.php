<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_voucher_item extends Model
{
	protected $table = 'tbl_voucher_item';
	protected $primaryKey = "voucher_item_id";
    public $timestamps = false;
}
