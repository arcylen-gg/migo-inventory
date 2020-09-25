<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_settings extends Model
{
	protected $table = 'tbl_settings';
	protected $primaryKey = "settings_id";
    public $timestamps = true;
}