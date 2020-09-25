<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_debit_memo_replace_line extends Model
{
   protected $table = 'tbl_debit_memo_replace_line';
   protected $primaryKey = "dbline_replace_id";
   public $timestamps = false;
}
