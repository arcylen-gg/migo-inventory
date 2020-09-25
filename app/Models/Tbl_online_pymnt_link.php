<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_online_pymnt_link extends Model
{
	protected $table = 'tbl_online_pymnt_link';
	protected $primaryKey = "link_id";
    public $timestamps = false;
}