<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_ecommerce_setting extends Model
{
    protected $table = 'tbl_ecommerce_setting';
	protected $primaryKey = "ecommerce_setting_id";
    public $timestamps = false;
    
    
    public function scopecountsel($query,$shop_id = 0, $code = '')
    {
        $query->where('shop_id',$shop_id)->where('ecommerce_setting_code',$code);
        return $query;
    }
    public function scopesel($query, $shop_id = 0)
    {
        $query->where('shop_id',$shop_id);
        return $query;
    }
}
