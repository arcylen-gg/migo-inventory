<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;


use App\Models\Tbl_user;
use App\Models\Tbl_category;
use App\Models\Tbl_product;
use App\Models\Tbl_item;
use App\Models\Tbl_product_vendor;
use App\Models\Tbl_mlm_product_points;
use App\Models\Tbl_mlm_item_points;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_item_discount;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_stairstep_settings;
use App\Models\Tbl_rank_repurchase_cashback_item;

use App\Globals\Utilities;
use App\Globals\Mlm_plan;
use App\Globals\AuditTrail;
class MLM_ProductController extends Member
{
    public function index()
    {
        $access = Utilities::checkAccess('mlm-product-points', 'access_page');
        if($access == 0)
        {
            return $this->show_no_access(); 
        }

        $shop_id = $this->checkuser('user_shop');
        
        
        $data['active_plan_product_repurchase'] = Mlm_plan::get_all_active_plan_repurchase($shop_id);
	    $data['membership_active'] = Tbl_membership::getactive(0, $shop_id)->get();
        $data['rank_settings'] = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)->get();
	    // setup product on first try : no error on leftjoin
	    $_inventory = Tbl_item::where("tbl_item.shop_id",$shop_id);

        $item_search = Request::input('item');
        if($item_search != null)
        {
            $_inventory = $_inventory->where('item_sku', 'like', '%' . $item_search . '%')
            ->orWhere('item_name', 'like', '%' . $item_search . '%');
        }
        $merchant_item = Request::input('merchant_item');
        if($merchant_item)
        {
            $_inventory = $_inventory->type()->category()
            ->where('item_id', $merchant_item)
            ->where("tbl_item.shop_id",$shop_id)
            ->paginate(10);
        }
        else
        {
            $_inventory = $_inventory->where('tbl_item.archived', 0)->orderBy('tbl_item.item_id','asc')->paginate(10);
        }
        

	    $this->setup_points_initial($_inventory);
	    // end setup
	    
	    $_inventory  = Tbl_item::orderBy('tbl_item.item_id','asc');
        
        $item_search = Request::input('item');
        if($item_search != null)
        {
            $_inventory = $_inventory->where('item_sku', 'like', '%' . $item_search . '%')
            ->orWhere('item_name', 'like', '%' . $item_search . '%');

            // filter="status"
        }

        $merchant_item = Request::input('merchant_item');
        if($merchant_item)
        {
            $_inventory = $_inventory->type()->category()
            ->where('item_id', $merchant_item)
            ->where("tbl_item.shop_id",$shop_id)
            ->paginate(10);
        }
        else
        {
            $_inventory = $_inventory->where('tbl_item.archived', 0)->type()->category()
            ->where("tbl_item.shop_id",$shop_id)
            ->paginate(10);
        }
        


        foreach($_inventory as $key => $value)
        {
            foreach($data['membership_active'] as $key2 => $value2)
            {
                $item_points[$value2->membership_id] = Tbl_mlm_item_points::where('item_id', $value->item_id)->where('membership_id', $value2->membership_id)->first();
            }      
            
            foreach($data['rank_settings'] as $key2 => $value2)
            {
                $rank_cashback[$value2->stairstep_id] = Tbl_rank_repurchase_cashback_item::where('item_id', $value->item_id)->where('rank_id', $value2->stairstep_id)->first();
            }

            $_inventory[$key]->item_points   = $item_points;

            if (isset($item_points))
            {
                $_inventory[$key]->item_points   = $item_points;
            }
            else
            {
                $_inventory[$key]->item_points   = null;
            }
            

            if (isset($rank_cashback)) 
            {
                $_inventory[$key]->rank_cashback = $rank_cashback;
            }
            else
            {
                $_inventory[$key]->rank_cashback = null;
            }
        }
        // dd($_inventory);
	    $data['active'] = [];
        $add_count      = count($data['active_plan_product_repurchase']);
	    foreach($data['active_plan_product_repurchase'] as $key => $value)
	    {
    		$data['active'][$key]  = $value->marketing_plan_code;
            $data['active_label'][$key]  = $value->marketing_plan_label;

            if($value->marketing_plan_code == "STAIRSTEP")
            {
                $data['active'][$add_count]        = "STAIRSTEP_GROUP";
                $data['active_label'][$add_count]  = "Stairstep Group Bonus";  
                $data['active_label'][$key]        = "Stairstep";
                $add_count++;  
            }
            else if($value->marketing_plan_code == "RANK")
            {
                $data['active'][$add_count]        = "RANK_GROUP";
                $data['active_label'][$add_count]  = "Rank Group Bonus"; 
                $add_count++;
            }            
            else if($value->marketing_plan_code == "REPURCHASE_CASHBACK")
            {
                $data['active'][$add_count]        = "RANK_REPURCHASE_CASHBACK";
                $data['active_label'][$add_count]  = "Rank Repurchase Cashback"; 
                $add_count++;

                $data['active'][$add_count]        = "REPURCHASE_CASHBACK_POINTS";
                $data['active_label'][$add_count]  = "Repurchase Cashback Points"; 
                $add_count++;
            }
	    }

        $data['item']          = $this->iteminventory($_inventory, $data['active']);
        $data['_inventory']    = $_inventory;       
        $data['item_search']   = $item_search;
        // dd($data);
        
        return view('member.mlm_product.product', $data);
    }

    public function setup_points_initial($arr)
    {
        $shop_id = $this->user_info->shop_id;
        $membership = Tbl_membership::where('shop_id', $shop_id)->where('membership_archive', 0)->get();
        foreach($membership as $key => $value)
        {
            foreach($arr as $key2 => $value2)
            {
                $c = Tbl_mlm_item_points::where('item_id', $value2->item_id)->where('membership_id', $value->membership_id)->count();
                if($c == 0)
                {
                    $insert['item_points_stairstep'] = 0;
                    $insert['item_points_unilevel']  = 0;
                    $insert['item_points_binary']    = 0;
                    $insert['item_id']               = $value2->item_id;
                    $insert['membership_id'] = $value->membership_id;
                    Tbl_mlm_item_points::insert($insert);
                }
            }
        }   

        $rank = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)->get();
        foreach($rank as $key => $value)
        {
            foreach($arr as $key2 => $value2)
            {
                $c = Tbl_rank_repurchase_cashback_item::where('item_id', $value2->item_id)->where('rank_id', $value->stairstep_id)->count();
                if($c == 0)
                {
                    $insert_rank_cashback['amount']  = 0;
                    $insert_rank_cashback['item_id'] = $value2->item_id;
                    $insert_rank_cashback['rank_id'] = $value->stairstep_id;
                    Tbl_rank_repurchase_cashback_item::insert($insert_rank_cashback);
                }
            }
        }
    }

    public function iteminventory($_inventory = array(), $active)
    {
		
        // dd($_inventory[0]);
		$item = array();
	    foreach($_inventory as $key => $inventory)
	    {
	        
	        foreach($active as $key2 => $value2)
	        {
	           $item[$key][$value2]       =  $inventory->$value2;
	        }
            $item[$key]['points']        = $inventory->item_points;
            $item[$key]['rank_cashback'] = $inventory->rank_cashback;

	        $item[$key]['item_id']   	   	   	  = $inventory->item_id;
	        $item[$key]['item_name'] 	   		  = $inventory->item_name;
	        $item[$key]['item_sales_information'] = $inventory->item_sales_information;
	        $item[$key]['item_price'] 			  = $inventory->item_price;
	        $item[$key]['item_sku'] 			  = $inventory->item_sku;
            $item[$key]['item_img']               = $inventory->item_img;
            $item[$key]['item_show_in_mlm']       = $inventory->item_show_in_mlm;
	        
	    }
	    return $item;
	}
	// Dont remove this /// used in other function
	public static function checkuser($str = '')
	{
        $user_info = Tbl_user::where("user_email", Session('user_email'))->shop()->first();
        switch ($str) 
        {
            case 'user_id':
                return $user_info->user_id;
                break;
            case 'user_shop':
                return $user_info->user_shop;
                break;
            default:
                return '';
                break;
        }
    }   
    public function add_product_points()
    {
        // return $_POST;
        $old_mlm_item_points = Tbl_mlm_item_points::where('item_id', Request::input('item_id'))->first()->toArray();        

        $shop_id 							    = $this->checkuser('user_shop');
        $data['active_plan_product_repurchase'] = Mlm_plan::get_all_active_plan_repurchase($shop_id);
        $update 								= null;
        $membership = Tbl_membership::getactive(0, $shop_id)->get();

        $points                 = Request::input('membership_points');
        $rank_cashback_points   = Request::input('rank_cashback_points');
        $_rank                  = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)->get();
        foreach($membership as $key2 => $value2)
        {
            foreach($data['active_plan_product_repurchase'] as $key => $value)
            {
                    $update = [];
    	            $tablename 			 = $value->marketing_plan_code;
                    
                    $update[$tablename] = $points[$tablename][$value2->membership_id];
                    if($value->marketing_plan_code == "STAIRSTEP")
                    {
                        $update["STAIRSTEP_GROUP"] = $points["STAIRSTEP_GROUP"][$value2->membership_id];
                    }
                    else if($value->marketing_plan_code == "RANK")
                    {
                        $update["RANK_GROUP"] = $points["RANK_GROUP"][$value2->membership_id]; 
                    }                    
                    else if($value->marketing_plan_code == "REPURCHASE_CASHBACK")
                    {
                        $update["REPURCHASE_CASHBACK_POINTS"] = $points["REPURCHASE_CASHBACK_POINTS"][$value2->membership_id];

                        foreach($_rank as $rank)
                        {
                            $update_rank_cashback['amount']  = $rank_cashback_points[$rank->stairstep_id];
                            Tbl_rank_repurchase_cashback_item::where("item_id",Request::input("item_id"))->where("rank_id",$rank->stairstep_id)->update($update_rank_cashback);
                        } 
                    }


                    Tbl_mlm_item_points::where('item_id', Request::input('item_id'))
                    ->where('membership_id', $value2->membership_id)->update($update);
            }
        }

        if($update != null)
        {
            // Tbl_mlm_item_points::where('item_id', Request::input('item_id'))->update($update);

            // $new_mlm_item_points = Tbl_mlm_item_points::where('item_id', Request::input('item_id'))->first()->toArray();
            // AuditTrail::record_logs("Edited","mlm_item_points",Request::input('item_id'),serialize($old_mlm_item_points),serialize($new_mlm_item_points));
        }

        $data['response_status'] = "success_edit_points";
	    echo json_encode($data);
    }
    public function discount()
    {
        $access = Utilities::checkAccess('mlm-product-discount', 'access_page');
        if($access == 0)
        {
            return $this->show_no_access(); 
        }
    	$shop_id = $this->user_info->shop_id;
        $items = Tbl_item::where('tbl_item.archived', 0)
        ->orderBy('tbl_item.item_id','asc');
        $item_search = Request::input('item');
        if($item_search != null)
        {
            $items = $items->where('item_sku', 'like', '%' . $item_search . '%')
            ->orWhere('item_name', 'like', '%' . $item_search . '%');
        }

    	$data['items'] = $items->type()->category()->where("tbl_item.shop_id",$shop_id)->paginate(10);
    	$data['membership_active'] = Tbl_membership::getactive(0, $shop_id)->get();
    	$item_discount = [];
    	$item_percentage = [];
    	foreach($data['items'] as $key => $item)
    	{
    		foreach($data['membership_active'] as $value)
    		{
    			$item_discount[$item->item_id][$value->membership_id] = Tbl_mlm_item_discount::where('item_id', $item->item_id)->where('membership_id', $value->membership_id)->value('item_discount_price'); 
    			$item_percentage[$item->item_id][$value->membership_id] = Tbl_mlm_item_discount::where('item_id', $item->item_id)->where('membership_id', $value->membership_id)->value('item_discount_percentage'); 
    		}
    	}
    	$data['item_discount'] = $item_discount;
    	$data['item_discount_percentage'] = $item_percentage;
        $data['item_search'] = $item_search;
    	// dd($data);
    	return view('member.mlm_product.discount', $data);
    }
    public function discount_add()
    {
    	$membership_input = Request::input('membership_id');
    	$membership_price = Request::input('price');
    	$item_id = Request::input('item_id');
    	$percentage = Request::input('percentage');
    	foreach($membership_input as $key => $membership)
    	{
    		$count = Tbl_mlm_item_discount::where('item_id', $item_id)->where('membership_id', $key)->count();
    		if($count == 0)
    		{
    			$insert['item_discount_price'] = $membership_price[$key]; 
    			$insert['membership_id'] = $key;
    			$insert['item_id'] = $item_id;
    			$insert['item_discount_percentage'] = $percentage[$key];
    			$mlm_item_discount_id = Tbl_mlm_item_discount::insertGetId($insert);

                $mlm_item_discount = Tbl_mlm_item_discount::where('item_discount_d', $mlm_item_discount_id)->first()->toArray();
                AuditTrail::record_logs("Added","mlm_discount_item",$mlm_item_discount_id ,"",serialize($mlm_item_discount));
    		}
    		else
    		{
                $old_mlm_item_discount = Tbl_mlm_item_discount::where('item_id', $item_id)->where('membership_id', $key)->first()->toArray();

                $update['item_discount_price'] = $membership_price[$key]; 
    			$update['item_discount_percentage'] = $percentage[$key];
    			$count = Tbl_mlm_item_discount::where('item_id', $item_id)->where('membership_id', $key)->update($update);

                $new_mlm_item_discount = Tbl_mlm_item_discount::where('item_discount_d', $old_mlm_item_discount["item_discount_d"])->first()->toArray();
                AuditTrail::record_logs("Edited","mlm_discount_item",$new_mlm_item_discount["item_discount_d"] ,serialize($old_mlm_item_discount),serialize($new_mlm_item_discount));
    		}
    		$data['response_status'] = "success_add_slot";
    	}
    	
    	echo json_encode($data);

    }
    public function product_repurchase_points()
    {
        
    }
    public function set_all_points()
    {
        // return $_POST;
        $shop_id = $this->checkuser('user_shop');
        $plan_code_id = Request::input('marketing_plan_code_id');
        $membership_id = Request::input('membership_id');
        $percentage = Request::input('percentage');

        $plan = Tbl_mlm_plan::where('marketing_plan_code_id', $plan_code_id)->first();
        // marketing_plan_code
        $items = Tbl_item::where("shop_id",$shop_id)
        ->where('archived', 0)->orderBy('tbl_item.item_id','asc')->get();

        foreach ($items as $key => $value) 
        {  
            
            if($value->promo_price >= 1)
            {
                $now = Carbon::now();
                if($value->start_promo_date <= $now && $value->end_promo_date >= $now)
                {
                    $update[$plan->marketing_plan_code] = ($value->promo_price/100) * $percentage;
                    Tbl_mlm_item_points::where('item_id', $value->item_id)->where('membership_id', $membership_id)->update($update);
                }
                else
                {
                    $update[$plan->marketing_plan_code] = ($value->item_price/100) * $percentage;
                    Tbl_mlm_item_points::where('item_id', $value->item_id)->where('membership_id', $membership_id)->update($update);
                }
            }
            else
            {
                $update[$plan->marketing_plan_code] = ($value->item_price/100) * $percentage;
                Tbl_mlm_item_points::where('item_id', $value->item_id)->where('membership_id', $membership_id)->update($update);
            }
        }
        $data['response_status'] = 'success_edit_all_points';
        return $data;
    }
}
