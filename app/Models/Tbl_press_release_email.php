<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_press_release_email extends Model
{
	protected $table = 'tbl_press_release_email';
	protected $primaryKey = "email_id";
    public $timestamps = false;
}