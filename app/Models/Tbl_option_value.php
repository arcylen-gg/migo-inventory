<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_option_value extends Model
{
	protected $table = 'tbl_option_value';
	protected $primaryKey = "option_value_id";
    public $timestamps = false;
}