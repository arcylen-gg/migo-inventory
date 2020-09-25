<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_cashback_points_distribute extends Model
{
	protected $table = 'tbl_cashback_points_distribute';
	protected $primaryKey = "cashback_distribute_id";
    public $timestamps = false;
}