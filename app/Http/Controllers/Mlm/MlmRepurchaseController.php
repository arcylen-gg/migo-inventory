<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use Session;
use Carbon\Carbon;
use App\Globals\Item;
use App\Globals\Warehouse;
use App\Globals\Mlm_plan;
use App\Globals\Mlm_repurchase;
use App\Globals\Mlm_repurchase_member;

use App\Models\Tbl_warehouse;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_item_discount;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_item;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_item_code;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_user;
use App\Globals\Item_code;
class MlmRepurchaseController extends Mlm
{
    public function index()
    {
    	$data["_item"] = Mlm_repurchase_member::get_all_items(Self::$shop_id, Self::$slot_id);
        $data["cart"] = $this->cart();
    	return view('mlm.repurchase.repurchase', $data);
    }

    public function cart()
    {
        $data["_cart"] = Mlm_repurchase_member::get_item_cart();
        return view('mlm.repurchase.repurchase_cart', $data);
    }

    public function add_cart()
    {
        $item_id = Request::input("item_id");
        $quantity = Request::input("quantity");
        $slot = Tbl_mlm_slot::where('slot_id', Self::$slot_id)->first();
        if ($slot) 
        {
            Mlm_repurchase_member::add_to_cart($item_id, $quantity, $slot->slot_membership);
        }
        else
        {
            Mlm_repurchase_member::add_to_cart($item_id, $quantity);
        }

        echo json_encode("success");
    }

    public function remove_item()
    {
        $item_id = Request::input("item_id");

        Mlm_repurchase_member::remove_from_cart($item_id);

        echo json_encode("success");
    }

    public function clear_cart()
    {
        Mlm_repurchase_member::clear_all_cart();

        echo json_encode("success");
    }

    public function checkout()
    {
        return view("mlm.repurchase.checkout");
    }
    public function checkout_submit()
    {
        // return $_POST;
        $cart = Mlm_repurchase_member::get_item_cart();
        foreach ($cart as $key => $value) {
            $input['item_id'][$key] = $key; 
            $input['quantity'][$key] = $value['quantity'];
        }
        $input['slot_id'] = Self::$slot_id;
        $input['discount_card_log_id'] = null;
        $input['customer_id'] = Self::$customer_id;
        $input['item_code_customer_email'] = Self::$customer_info->email;
        $input['item_code_paid'] = 1;
        $input['item_code_product_issued'] = 0;
        $input['item_code_date_created'] = Carbon::now();
        $input['shop_id'] = Self::$shop_id;
        $input['payment_type_choose'] = 3;
        $warehouse = Tbl_warehouse::where('main_warehouse', 1)->where('warehouse_shop_id', Self::$shop_id)->first();
        $input['warehouse_id'] = $warehouse->warehouse_id;
        $data['use_item_code_auto'] = 1;
        $user = Tbl_user::where('user_shop', Self::$shop_id)->first();
        
        $data    = Item_code::add_code($input,Self::$shop_id, $user->user_id, $warehouse->warehouse_id);

        return $data;
    }
}