<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_item_average_cost_per_warehouse extends Model
{
	protected $table = 'tbl_item_average_cost_per_warehouse';
	protected $primaryKey = "iacpw_id";
    public $timestamps = false;
}