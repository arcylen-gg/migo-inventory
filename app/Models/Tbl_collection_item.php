<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_collection_item extends Model
{
	protected $table = 'tbl_collection_item';
	protected $primaryKey = "collection_item_id";
    public $timestamps = false;
}