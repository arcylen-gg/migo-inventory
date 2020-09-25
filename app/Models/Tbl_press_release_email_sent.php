<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_press_release_email_sent extends Model
{
	protected $table = 'tbl_sent_email_press_release';
	protected $primaryKey = "email_id";
    public $timestamps = false;
}
