<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_vendor_address extends Model
{
	protected $table = 'tbl_vendor_address';
	protected $primaryKey = "ven_addr_id";
    public $timestamps = false;
}