<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_direct_gc_logs extends Model
{
	protected $table = 'tbl_direct_gc_logs';
	protected $primaryKey = "gc_logs_id";
    public $timestamps = false;
}