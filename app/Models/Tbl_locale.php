<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_locale extends Model
{
	protected $table = 'tbl_locale';
	protected $primaryKey = "locale_id";
    public $timestamps = false;
}