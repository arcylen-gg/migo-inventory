<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_sms_template extends Model
{
	protected $table = 'tbl_sms_template';
	protected $primaryKey = "sms_temp_id";
    public $timestamps = false;
}