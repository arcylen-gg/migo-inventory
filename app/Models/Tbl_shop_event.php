<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_shop_event extends Model
{
	protected $table = 'tbl_shop_event';
	protected $primaryKey = "event_id";
    public $timestamps = true;
}