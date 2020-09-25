<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_mlm_indirect_setting extends Model
{
	protected $table = 'tbl_mlm_indirect_setting';
	protected $primaryKey = "indirect_seting_id";
    public $timestamps = false;
    
    //indirect_seting_percent  0 = fixed
    //indirect_seting_percent  1 = percentage
}
