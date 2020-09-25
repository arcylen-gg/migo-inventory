<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_project extends Model
{
   	protected $table = 'tbl_project';
	protected $primaryKey = "project_id";
    public $timestamps = false;


    public function scopeJoinType($query)
    {
    	$query->join("tbl_project_type", "tbl_project_type.project_type_id", "=", "tbl_project.project_type_id");
    }
}