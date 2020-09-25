<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_social_network_keys extends Model
{
	protected $table = 'tbl_social_network_keys';
	protected $primaryKey = "keys_id";
    public $timestamps = false;
}