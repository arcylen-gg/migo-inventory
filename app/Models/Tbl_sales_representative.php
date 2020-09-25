<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_sales_representative extends Model
{
	protected $table = 'tbl_sales_representative';
	protected $primaryKey = "sales_rep_id";
    public $timestamps = false;
}