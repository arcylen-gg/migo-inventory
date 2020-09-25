<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_report_field extends Model
{
	protected $table = 'tbl_report_field';
	protected $primaryKey = "report_field_id";
    public $timestamps = false;
}