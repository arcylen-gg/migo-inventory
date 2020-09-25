<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_matching_log extends Model
{
	protected $table = 'tbl_mlm_matching_log';
	protected $primaryKey = "matching_log";
    public $timestamps = false;
}