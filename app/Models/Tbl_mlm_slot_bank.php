<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_slot_bank extends Model
{
    protected $table = 'tbl_mlm_slot_bank';
	protected $primaryKey = "mlm_slot_bank_id";
    public $timestamps = false;

    public function scopeBank_details($query)
    {
    	 $query->leftjoin('tbl_payout_bank', 'tbl_payout_bank.payout_bank_id', '=', 'tbl_mlm_slot_bank.payout_bank_id');
    	 return $query;
    }
}