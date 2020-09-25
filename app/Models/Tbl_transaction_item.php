<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_transaction_item extends Model
{
	protected $table = 'tbl_transaction_item';
	protected $primaryKey = "transaction_item_id";
	public $timestamps = false;

	public function scopeTransaction_list($query)
	{
		return $query->leftjoin('tbl_transaction_list','tbl_transaction_list.transaction_list_id','=','tbl_transaction_item.transaction_list_id');
	}
	public function scopeTransaction($query)
	{
		return $query->leftjoin('tbl_transaction','tbl_transaction_list.transaction_id','=','tbl_transaction.transaction_id');
	}
}