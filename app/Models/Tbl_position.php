<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_position extends Model
{
	protected $table = 'tbl_position';
	protected $primaryKey = "position_id";
    public $timestamps = true;
}