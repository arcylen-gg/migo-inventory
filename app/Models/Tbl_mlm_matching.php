<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_matching extends Model
{
	protected $table = 'tbl_mlm_matching';
	protected $primaryKey = "matching_settings_id";
    public $timestamps = false;
}