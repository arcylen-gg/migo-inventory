<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_vendor_attachment extends Model
{
    protected $table = 'tbl_vendor_attachment';
	protected $primaryKey = "vendor_attachment_id";
    public $timestamps = false;
}
