<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_commission extends Model
{
	protected $table = 'tbl_commission';
	protected $primaryKey = "commission_id";
    public $timestamps = false;

    public function scopeItem($query)
    {
    	return $query->leftjoin('tbl_commission_item','tbl_commission.commission_id','=','tbl_commission_item.commission_id')
    				 ->leftjoin('tbl_item','tbl_item.item_id','=','tbl_commission_item.item_id');
    }
    public function scopeSalesrep($query)
    {
    	return $query->selectRaw('*, tbl_employee.first_name as salesrep_fname, tbl_employee.middle_name as salesrep_mname,tbl_employee.last_name as salesrep_lname')
                     ->leftjoin('tbl_employee','employee_id','=','agent_id');
    }
    public function scopeInvoice($query)
    {
        return $query->leftjoin('tbl_commission_invoice','tbl_commission_invoice.commission_id','=','tbl_commission.commission_id')
                     ->leftjoin('tbl_customer_invoice','inv_id','=','invoice_id');
    }
    public function scopeCustomer($query)
    {
        return $query->leftjoin('tbl_customer','tbl_customer.customer_id','=','tbl_commission.customer_id');
    }
}