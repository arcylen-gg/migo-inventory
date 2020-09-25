<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_press_release_recipient extends Model
{
	protected $table = 'tbl_press_release_recipients';
	protected $primaryKey = "recipient_id";
    public $timestamps = false;
}