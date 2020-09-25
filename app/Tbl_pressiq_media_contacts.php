<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tbl_pressiq_media_contacts extends Model
{
	protected $table = 'tbl_pressiq_media_contacts';
    protected $primaryKey = "contact_id";
    public $timestamps = false;
}
