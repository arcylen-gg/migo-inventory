<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_sms_logs extends Model
{
	protected $table = 'tbl_sms_logs';
	protected $primaryKey = "sms_logs_id";
    public $timestamps = false;
}