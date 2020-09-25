<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_binary_pairing extends Model
{
	protected $table = 'tbl_mlm_binary_pairing';
	protected $primaryKey = "pairing_id";
    public $timestamps = false;
}
