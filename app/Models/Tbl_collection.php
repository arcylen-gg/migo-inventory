<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_collection extends Model
{
	protected $table = 'tbl_collection';
	protected $primaryKey = "collection_id";
    public $timestamps = false;
}