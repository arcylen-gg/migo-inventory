<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_acctg_transaction extends Model
{
	protected $table = 'tbl_acctg_transaction';
	protected $primaryKey = "acctg_transaction_id";
    public $timestamps = false;

    public function scopeList($query)
    {
    	return $query->leftjoin("tbl_acctg_transaction_list","tbl_acctg_transaction_list.acctg_transaction_id","=","tbl_acctg_transaction.acctg_transaction_id");
    }
    public function scopeUser($query)
    {
    	return $query->leftjoin("tbl_user","transaction_user_id","=","user_id");
    }
}