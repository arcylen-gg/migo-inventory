<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_item_default_landing_cost extends Model
{
	protected $table = 'tbl_item_default_landing_cost';
	protected $primaryKey = "default_cost_id";
    public $timestamps = false;
}