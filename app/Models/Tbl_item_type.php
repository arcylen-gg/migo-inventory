<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tbl_item_type extends Model
{
    protected $table = 'tbl_item_type';
	protected $primaryKey = "item_type_id";
    public $timestamps = true;
}
