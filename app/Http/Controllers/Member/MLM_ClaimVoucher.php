<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use App\Globals\Membership_package;
use App\Globals\Membership_code;
use Validator;
use App\Models\Tbl_membership_package;
use App\Models\Tbl_voucher;
use App\Models\Tbl_voucher_item;
use App\Models\Tbl_item_code;
use App\Models\Tbl_item;
use Crypt;
use App\Globals\Item;
use App\Globals\AuditTrail;
use App\Globals\Utilities;
class MLM_ClaimVoucher extends Member
{
    public function index()
    {
    	$access = Utilities::checkAccess('mlm-membership-claim-voucher', 'access_page');
        if($access == 0)
        {
        	return $this->show_no_access(); 
        }

    	$data["page"] 		= "Vouchers";
        $data["_voucher"]	= Tbl_voucher::where("voucher_claim_status","!=",2)
        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_voucher.voucher_customer')
        ->where('shop_id', $this->user_info->shop_id)
        ->get();

        return view('member.mlm_claim_voucher.mlm_voucher_list',$data);
    }

    public function claim()
    {
    	$access = Utilities::checkAccess('mlm-membership-claim-voucher', 'claim');
        if($access == 0)
        {
        	return $this->show_no_access(); 
        }
        
		$data['_message']         = null;
		$data['voucher']          = null;
		$data['_voucher_product'] = null; 

		if(isset($_POST['voucher_id']))
		{
			$admin_pass = Crypt::decrypt($this->user_info->user_password);

	        Validator::extend('foo', function($attribute, $value, $parameters)
            {
            	return $value == $parameters[0];
            });

	        $rules['account_password'] = 'required|foo:'.$admin_pass;
	        $rules['voucher_code']     = 'required|exists:tbl_voucher,voucher_code,voucher_id,'.Request::input('voucher_id');
	        $rules['voucher_id']       = 'required|exists:tbl_voucher,voucher_id,voucher_code,'.Request::input('voucher_code');
	        $messages  = ['account_password.foo'=>'The :attribute is invalid.',];
			$validator = Validator::make(Request::input(),$rules, $messages);

			if($validator->fails())
			{
				$messages = $validator->messages();
				$data['_message']['account_password'] = $messages->get('account_password');
				$data['_message']['voucher_id'] = $messages->get('voucher_id');
				$data['_message']['voucher_code'] = $messages->get('voucher_code');
			}
			else
			{
				$voucher_id = Request::input('voucher_id');
				$data['voucher'] = 	Tbl_voucher::find($voucher_id);
				$data['_voucher_product']  = Tbl_voucher_item::select('tbl_voucher_item.*','tbl_item.item_price','tbl_item.item_quantity','tbl_item.item_name')
				->leftjoin('tbl_item','tbl_item.item_id','=', 'tbl_voucher_item.item_id')
				->where('tbl_voucher_item.voucher_id', $voucher_id)
			    ->get();
			    $data['item_bundle'] =[];
			    foreach($data['_voucher_product'] as $key => $value)
			    {
			    	if($value->voucher_is_bundle == 1)
			    	{
			    		$data['_voucher_product'][$key]->item_bundle = Item::get_item_bundle($value->item_id);
			    		$data['item_bundle'][$key] =Item::get_item_bundle($value->item_id);
			    	}
			    }
			}
		}
		// dd($data);
        return view('member.mlm_claim_voucher.mlm_claim_voucher',$data);
    }
	public function process()
	{
		$data['_error'] = null;
		$data['query'] = 0;
		$current_admin_pass = Crypt::decrypt($this->user_info->user_password);


		$voucher_id = Request::input('voucher_id');
		$voucher = Tbl_voucher::find($voucher_id);

		$request['account_password'] = Request::input('account_password');
		$rules['account_password'] = 'required|check_pass:'.$current_admin_pass;

		$request['voucher_id'] = $voucher_id;
		$rules['voucher_id'] = 'required|exists:tbl_voucher,voucher_id,voucher_claim_status,0';

		$_voucher_product = Tbl_voucher_item::select('tbl_voucher_item.*','tbl_item.item_price','tbl_item.item_quantity','tbl_item.item_name')
		->leftjoin('tbl_item','tbl_item.item_id','=', 'tbl_voucher_item.item_id')
		->where('tbl_voucher_item.voucher_id', $voucher_id)
	    ->get();



	    if($_voucher_product)
	    {
	    	/**
		    * VALIDATOR REQUEST PRODUCT VOUCHER
		    */

	        foreach($_voucher_product as $key => $val)
	        {
	            $request['product_'.$val->item_id] = $val->voucher_item_quantity;
	        }

	        foreach( $_voucher_product as $key => $val)
	        {
	        }

	        foreach( $_voucher_product as $key => $val)
	        {
	        }
	    }
	    $messages['voucher_id.exists'] = "The selected vouher might have been already processed or cancelled.";
	    $messages['account_password.check_pass'] = "Invalid Password.";

	    Validator::extend('check_pass', function($attribute, $value, $parameters)
        {
    		return $value == $parameters[0];
        });



		$validator = Validator::make($request , $rules ,$messages);



		if($validator->fails())
		{
			$data['_error'] = $validator->messages()->all();
		}
		else
		{
			
            $update_v['voucher_claim_status'] = 1;
			Tbl_voucher::where('voucher_id', $voucher_id)->lockForUpdate()->update($update_v);

			$count = Tbl_voucher::invoice()->where("voucher_id",$voucher_id)->count();
			if($count >= 1)
			{
				$claim_data = Tbl_voucher::invoice()->where("voucher_id",$voucher_id)->first()->toArray();
		    	AuditTrail::record_logs("claim","voucher",$voucher_id,"",serialize($claim_data));
			}
		    
		}

		return $data;
	}

	public function void()
	{

		$data['_error'] = null;
		$current_admin_pass = Crypt::decrypt($this->user_info->user_password);
		
		$voucher_query 		   = Tbl_voucher::find(Request::input('voucher_id'));	
		$voucher_id            = Request::input("voucher_id");
        $request['voucher_id'] = Request::input('voucher_id');
        $request['status']     = $voucher_query->voucher_claim_status;
		$rules['voucher_id']   = 'required';
		$rules['status']   	   = 'max:0|min:0';

		$request['account_password'] = Request::input('account_password');
		$rules['account_password'] = 'required|check_pass:'.$current_admin_pass;

		$messages['account_password.check_pass'] = "Invalid Password.";
		$messages['voucher_id.exists'] = "The selected voucher might have been already processed or cancelled.";
		$messages['voucher_id.voucher_void'] = "The selected voucher was already cancelled.";
		$messages['status.max'] = "The selected voucher was already cancelled.";
		$messages['status.min'] = "The selected voucher was already cancelled.";

		$_voucher_product = Tbl_voucher_item::select('tbl_voucher_item.*',"used",'tbl_item.item_price','tbl_item.item_quantity','tbl_item.item_name')
																	->leftjoin('tbl_voucher','tbl_voucher.voucher_id','=', 'tbl_voucher_item.voucher_id')
																	->leftjoin('tbl_item_code_invoice','tbl_item_code_invoice.item_code_invoice_id','=', 'tbl_voucher.voucher_invoice_product_id')
																	->leftjoin('tbl_item_code','tbl_item_code.item_code_invoice_id','=', 'tbl_item_code_invoice.item_code_invoice_id')
																	->leftjoin('tbl_item','tbl_item.item_id','=', 'tbl_item_code.item_id')
																	->where('tbl_voucher_item.voucher_id', $voucher_id)
																    ->get();
		if($_voucher_product)
	    {

	    	/**
		    * VALIDATOR REQUEST PRODUCT VOUCHER
		    */

	        foreach($_voucher_product as $key => $val)
	        {
	            $request['product_'.$val->item_id] = $val->used;
	        }

	        /**
	        * VALIDATOR RULES PRODUCT VOUCHER
	        */
	        foreach( $_voucher_product as $key => $val)
	        {
	        	$rules['product_'.$val->item_id] = 'check_use';
	        }


	        foreach( $_voucher_product as $key => $val)
	        {
	        	$messages['product_'.$val->item_id.'.check_use'] = 'The :attribute was already used.';
	        }
	        
	        
	    }


	    
	    Validator::extend('voucher_void', function($attribute, $value, $parameters)
        {

        	if($parameters[0] == 2)
        	{
        		return false;
        	}
        	else
        	{	
        		return true;
        	}
        });

	    Validator::extend('check_use', function($attribute, $value, $parameters)
        {
    		return $value == 0;
        });


        Validator::extend('check_pass', function($attribute, $value, $parameters)
        {
    		return $value == $parameters[0];
        });
		$validator = Validator::make($request , $rules ,$messages);


		if($validator->fails())
		{
			$data['_error'] = $validator->messages()->all();
		}
		else
		{	
			/**
			 * RETURN THE DEDUCTED PTS FROM SLOT WALLET IF VOUCHER IS FROM A MEMBER
			 */

			
			if($voucher_query->slot_id != 0)
			{
				$voucher_slot = Tbl_slot::find($voucher_query->slot_id);
				$updated_wallet = $voucher_slot->slot_wallet + $voucher_query->total_amount;
			}
			/**
			 * IF THE VOUCHER STATUS IS "PROCESSED" RETURN THE DEDUCTED INVENTORY
			 */
			$voucher_product = Tbl_voucher_item::select('tbl_voucher_item.*','tbl_item_code.item_code_id',"used",'tbl_item.item_price','tbl_item.item_quantity','tbl_item.item_name')
																	->leftjoin('tbl_voucher','tbl_voucher.voucher_id','=', 'tbl_voucher_item.voucher_id')
																	->leftjoin('tbl_item_code_invoice','tbl_item_code_invoice.item_code_invoice_id','=', 'tbl_voucher.voucher_invoice_product_id')
																	->leftjoin('tbl_item_code','tbl_item_code.item_code_invoice_id','=', 'tbl_item_code_invoice.item_code_invoice_id')
																	->leftjoin('tbl_item','tbl_item.item_id','=', 'tbl_item_code.item_id')
																	->where('tbl_voucher_item.voucher_id', $voucher_id)
																    ->get()->toArray();

			if($voucher_query->voucher_claim_status == 1)
			{

				foreach ($voucher_product as $key => $product)
				{
					$current_product = Tbl_item::find($product['item_id']);
					$updated_stock = $current_product->item_quantity + $product['voucher_item_quantity'];
					Tbl_item::where('item_id',$product['item_id'])->update(['item_quantity'=> $updated_stock]);
				}
			}


            /**
			 * CHANGE THE VOUCHER STATUS TO CANCELLED;
			 */

            $update_v['voucher_claim_status'] = 2;
			Tbl_voucher::where('voucher_id', Request::input('voucher_id'))->update($update_v);

			$_voucher_id = Request::input('voucher_id');
		    $claim_data = Tbl_voucher::invoice()->where("voucher_id",$_voucher_id)->first()->toArray();
		    AuditTrail::record_logs("void","voucher",$_voucher_id,"",serialize($claim_data));

			/**
			 * DELETE ALL THE RELATED PRODUCT_CODE OF THE VOUCHER
			 */
			foreach ($voucher_product as $key => $value)
			{
				Tbl_item_code::where('item_code_id', $value['item_code_id'])->update(["archived"=>1]);
			}

		}

		return $data;
	}
}