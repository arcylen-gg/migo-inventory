<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_temp_customer_invoice_line extends Model
{
    protected $table = 'tbl_temp_customer_invoice_line';
	protected $primaryKey = "invline_id";
    public $timestamps = true;

    public function scopeUm($query)
    {
        return $query->leftjoin("tbl_unit_measurement_multi", "multi_id", "=", "invline_um");
    }
    public function scopeInvoice_item($query)
    {
    	return $query->join("tbl_item","tbl_item.item_id","=","invline_item_id");
    }
}
