<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_user;
use App\Models\Tbl_product;
use App\Models\Tbl_variant;
use App\Models\Tbl_category;
use App\Models\Tbl_product_vendor;
use Crypt;
use Redirect;
use Request;
use View;
use Session;
use DB;


class InventoryController extends Member
{
	public function index(){
	    $shop_id = $this->checkuser('user_shop');
	    $type = Tbl_category::sel($shop_id)->orderBy('type_name','asc')->get();
	    $_inventory = Tbl_product::getinventory($shop_id)->orderBy('tbl_product.product_id','asc')->paginate(20);
	    $vendor = Tbl_product_vendor::sel($shop_id)->orderBy('vendor_name','asc')->get();
	   // dd($_inventory);
	   $data = '';
	   
	    $data['item'] = $this->iteminventory($_inventory);
	    $data['product_type'] = $type;
	    $data['product_vendor'] = $vendor;
	    $data['_inventory'] = $_inventory;
	   // dd($data);
	    return view('member.product.inventory.index',$data);
	}
	public function filter(){
		$trigger = Request::input('trigger');
		$quantity = Request::input('quantity');
		$filter = Request::input('filter');
		$quantity_sel = Request::input('quantity_sel');
		$symbol = '';
		$arr['trigger'] = $trigger;
		$arr['quantity'] = $quantity;
		$arr['filter'] = $filter;
		$arr['quantity_sel'] = $quantity_sel;
		// dd($arr);
		if($quantity_sel != ''){
			if($quantity_sel == 'equal'){
				$symbol = '=';
			}
			if($quantity_sel == 'not equal'){
				$symbol = '!=';
			}
			if($quantity_sel == 'less than'){
				$symbol = '<';
			}
			if($quantity_sel == 'greater than'){
				$symbol = '>';
			}
		}
		$shop_id = $this->checkuser('user_shop');
		$_inventory = Tbl_product::getinventory($shop_id);
		// dd($_inventory->get());
		switch ($trigger) {
			case 'type':
				$_inventory->where('tbl_product.product_type',$filter)->orderBy('tbl_product.product_id','asc')->get();
				
				break;
			case 'vendor':
				$_inventory->where('tbl_product.product_vendor',$filter)->orderBy('tbl_product.product_id','asc')->get();
				break;
			
			case 'quantity':
				$_inventory->where('view_product_variant.variant_inventory_count',$symbol,$quantity)->orderBy('tbl_product.product_id','asc')->get();
				break;
				
			default:
				// code...
				break;
		}
		
		$data['item'] = $this->iteminventory($_inventory->get());
		
		return view('member.product.inventory.tblinventory',$data);
	}
	public function iteminventory($_inventory = array()){
		
		$item = array();
	    foreach($_inventory as $key => $inventory){
	        
	        $item[$key]['product_id'] = $inventory->product_id;
	        $item[$key]['variant_id'] = $inventory->variant_id;
	        $item[$key]['product_name'] = $inventory->product_name;
	        $item[$key]['product_description'] = $inventory->product_description;
	        $var = $inventory->variant_name;
	        $exvar = explode("•",$var);
	        $strvar = '';
	        foreach($exvar as $v){
	            if($strvar != ''){
	                $strvar.='/';
	            }
	            $strvar.=$v;
	        }
	        
	        $item[$key]['variant_name'] = $strvar;
	        $item[$key]['variant_price'] = $inventory->variant_price;
	        $item[$key]['variant_sku'] = $inventory->variant_sku;
	        $item[$key]['variant_barcode'] = $inventory->variant_barcode;
	        $item[$key]['variant_inventory_count'] = $inventory->variant_inventory_count;
	        $item[$key]['variant_track_inventory'] = $inventory->variant_track_inventory;
	        $item[$key]['variant_allow_oos_purchase'] = $inventory->variant_allow_oos_purchase;
	        $item[$key]['image_path'] = $inventory->image_path;
	        
	    }
	    return $item;
	}
	public function checkuser($str = ''){
        $user_info = Tbl_user::where("user_email", Session('user_email'))->shop()->first();
        switch ($str) {
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
    
    public function updatquantity(){
		$quantity = Request::input('amount');
		$id = Request::input('content');
		$update['variant_inventory_count'] = $quantity;
		Tbl_variant::where('variant_id',$id)->update($update);
		return 'success';
    }
}