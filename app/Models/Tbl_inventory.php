<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_inventory extends Model
{
	protected $table = 'tbl_inventory';
	protected $primaryKey = "invty_id";
    public $timestamps = false;
}