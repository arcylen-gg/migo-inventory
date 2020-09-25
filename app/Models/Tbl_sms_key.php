<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_sms_key extends Model
{
	protected $table = 'tbl_sms_key';
	protected $primaryKey = "sms_id";
    public $timestamps = false;
}