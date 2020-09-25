<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_acctg_transaction_item extends Model
{
	protected $table = 'tbl_acctg_transaction_item';
	protected $primaryKey = "acctg_transaction_item_id";
    public $timestamps = false;
}