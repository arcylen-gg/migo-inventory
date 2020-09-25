<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payout_bank extends Model
{
    protected $table = 'tbl_payout_bank';
	protected $primaryKey = "payout_bank_id";
    public $timestamps = false;

    public function scopeShop($query, $shop_id)
    {
    	 $query->join('tbl_payout_bank_shop', 'tbl_payout_bank_shop.payout_bank_id', '=', 'tbl_payout_bank.payout_bank_id');
    	 $query->where("shop_id", $shop_id);
    	 return $query;
    }
}