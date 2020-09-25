<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_location extends Model
{
	protected $table = 'tbl_location';
	protected $primaryKey = "location_id";
    public $timestamps = false;
}