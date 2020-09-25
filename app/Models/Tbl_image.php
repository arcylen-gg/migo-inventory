<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_image extends Model
{
	protected $table = 'tbl_image';
	protected $primaryKey = "image_id";
    public $timestamps = true;
}