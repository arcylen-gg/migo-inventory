<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_delivery_method extends Model
{
    protected $table = 'tbl_delivery_method';
	protected $primaryKey = "delivery_method_id";
    public $timestamps = false;
}
