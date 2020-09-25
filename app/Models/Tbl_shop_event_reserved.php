<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_shop_event_reserved extends Model
{
	protected $table = 'tbl_shop_event_reserved';
	protected $primaryKey = "reservation_id";
    public $timestamps = false;
}