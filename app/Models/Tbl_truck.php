<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_truck extends Model
{
	protected $table = 'tbl_truck';
	protected $primaryKey = "truck_id";
    public $timestamps = true;
}