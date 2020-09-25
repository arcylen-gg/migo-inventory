<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_item_token extends Model
{
	protected $table = 'tbl_item_token';
	protected $primaryKey = "item_token_id";
    public $timestamps = false;
    
    public function scopeToken($query)
    {
    	$query->join('tbl_token_list','tbl_token_list.token_id','=','tbl_item_token.token_id');
    }

}
