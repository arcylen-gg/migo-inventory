<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_post extends Model
{
	protected $table = 'tbl_post';
	protected $primaryKey = "post_id";
    public $timestamps = false;
}