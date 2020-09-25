<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_order extends Model
{
	protected $table = 'tbl_order';
	protected $primaryKey = "tbl_order_id";
    public $timestamps = false;
    
    public function scopeSelByShop($query, $shop_id = 0, $last30days = '000-00-00'){
        $query->where('shop_id',$shop_id)
			  ->where('craeted_date','>=',$last30days);
	    return $query;
    }
    public function scopeSelPayment_stat($query, $payment_stat){
        
        if($payment_stat == null)
        {
            
        }
        else
        {
            if($payment_stat != '0')
            {  
                $query->where('payment_stat',$payment_stat);
	            return $query;
            }
        }
    }
    public function scopeSelStatus($query, $status)
    {
        
        if($status != null)
        {
           
	        $query->where('status',$status);
	        return $query;
        }
        elseif($status != '0')
        {
            $query->where('status',$status);
	        return $query;
        }
        else
        {
            $query->where('status','ready');
	        return $query;
        }
    }
    public function scopeSelFulfillment_status($query, $fulfillment_status){
        if($fulfillment_status == null)
        {
            
        }
        elseif($fulfillment_status == '0')
        {
            
        }
        else{
            $query->where('fulfillment_status',$fulfillment_status);
	        return $query;
        } 
    }
}