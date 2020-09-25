<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use DB;
class Tbl_warehouse_reorder extends Model
{
	protected $table = 'tbl_warehouse_reorder';
    protected $primaryKey = "wr_id";
    public $timestamps = false;
}