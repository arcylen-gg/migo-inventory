<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_bill_po extends Model
{
    protected $table = 'tbl_bill_po';
	protected $primaryKey = "bill_po_id";
    public $timestamps = false;

    public static function scopeBill()
    {    	
        return $query->join("tbl_bill", "tbl_bill.bill_id", "=", "billed_id");
    }
    public static function scopePo()
    {    	
        return $query->join("tbl_purchase_order", "tbl_purchase_order.po_id", "=", "purchase_order_id");
    }
}