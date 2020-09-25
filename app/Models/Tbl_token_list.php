<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_token_list extends Model
{
	protected $table = 'tbl_token_list';
	protected $primaryKey = "token_id";
    public $timestamps = false;
    
}
