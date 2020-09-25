<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_terms extends Model
{
	protected $table = 'tbl_terms';
	protected $primaryKey = "terms_id";
    public $timestamps = true;
}