<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_applicant extends Model
{
	protected $table = 'tbl_applicant';
	protected $primaryKey = "applicant_id";
    public $timestamps = false;
}