<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_user_access extends Model
{
	protected $table = 'tbl_user_access';
	protected $primaryKey = "acess_id";
    public $timestamps = false;
}