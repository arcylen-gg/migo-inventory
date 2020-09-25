<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_vendor_bank_record extends Model
{
	protected $table = 'tbl_vendor_bank_record';
	protected $primaryKey = "bank_record_id";
    public $timestamps = false;
}