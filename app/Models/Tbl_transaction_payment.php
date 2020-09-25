<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_transaction_payment extends Model
{
	protected $table = 'tbl_transaction_payment';
	protected $primaryKey = "transaction_payment_id";
    public $timestamps = false;
}