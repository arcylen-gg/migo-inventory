<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_item_token_log extends Model
{
	protected $table = 'tbl_item_token_log';
	protected $primaryKey = "token_log_id";
    public $timestamps = false;
    
}
