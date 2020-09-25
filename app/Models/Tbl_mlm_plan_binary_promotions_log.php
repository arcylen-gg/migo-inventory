<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_plan_binary_promotions_log extends Model
{
	protected $table = 'tbl_mlm_plan_binary_promotions_log';
	protected $primaryKey = "promotions_request_id";
    public $timestamps = false;

}
