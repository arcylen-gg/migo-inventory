<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_rank_points_log extends Model
{
	protected $table = 'tbl_rank_points_log';
	protected $primaryKey = "rank_points_log_id";
    public $timestamps = true;
}