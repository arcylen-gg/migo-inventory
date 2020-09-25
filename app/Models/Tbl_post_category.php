<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_post_category extends Model
{
	protected $table = 'tbl_post_category';
	protected $primaryKey = "post_category_id";
    public $timestamps = false;
}