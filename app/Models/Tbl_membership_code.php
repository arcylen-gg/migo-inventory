<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_membership_code extends Model
{
    protected $table = 'tbl_membership_code';
	protected $primaryKey = "membership_code_id";
    public $timestamps = false;
    
    public function scopePackage($query)
    {
        $query->join('tbl_membership_package', 'tbl_membership_package.membership_package_id', '=', 'tbl_membership_code.membership_package_id');
	   
	   
	    return $query;
    } 
    public function scopeActive($query)
    {
        $query->where("tbl_membership_code.archived", 0);
    }
    public function scopeShop($query, $shop_id)
    {
        $query->where("tbl_membership_code.shop_id", $shop_id);
    }
    public function scopeMembership($query)
    {
        $query->join('tbl_membership', 'tbl_membership.membership_id', '=', 'tbl_membership_package.membership_id');
        return $query;
    }
    
    public function scopeCustomer($query)
    {
        $query->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_membership_code.customer_id');
	    return $query;
    }
    public function scopePackage_item($query)
    {
        $query->leftjoin('tbl_membership_package_has', 'tbl_membership_package_has.membership_package_id', '=', 'tbl_membership_package.membership_package_id');
    }
    public function scopeInvoice($query)
    {
        $query->join('tbl_membership_code_invoice', 'tbl_membership_code_invoice.membership_code_invoice_id', '=', 'tbl_membership_code.membership_code_invoice_id');
      
      
        return $query;
    }
}
