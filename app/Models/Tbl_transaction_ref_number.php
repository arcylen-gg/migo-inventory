<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_transaction_ref_number extends Model
{
	protected $table = 'tbl_transaction_ref_number';
	protected $primaryKey = "trans_ref_number_id";
    public $timestamps = true;
}