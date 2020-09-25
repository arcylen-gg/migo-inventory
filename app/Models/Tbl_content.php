<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_content extends Model
{
	protected $table = 'tbl_content';
	protected $primaryKey = "content_id";
    public $timestamps = false;
}