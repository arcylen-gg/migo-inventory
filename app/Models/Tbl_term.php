<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_term extends Model
{
    protected $table = 'tbl_term';
	protected $primaryKey = "term_id";
    public $timestamps = false;
}
