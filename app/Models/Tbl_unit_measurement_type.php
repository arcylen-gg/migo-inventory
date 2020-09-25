<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_unit_measurement_type extends Model
{
	protected $table = 'tbl_unit_measurement_type';
	protected $primaryKey = "um_type_id";
    public $timestamps = false;
}