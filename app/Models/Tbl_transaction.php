<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_transaction extends Model
{
	protected $table = 'tbl_transaction';
	protected $primaryKey = "transaction_id";
	public $timestamps = false;

	public function scopeTransactionList($query)
    {
    	$query->join("tbl_transaction_list", "tbl_transaction.transaction_id", "=", "tbl_transaction_list.transaction_id");
    }

}