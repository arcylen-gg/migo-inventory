<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_tablet_data extends Model
{
	protected $table = 'tbl_tablet_data';
	protected $primaryKey = "data_id";
    public $timestamps = true;
}