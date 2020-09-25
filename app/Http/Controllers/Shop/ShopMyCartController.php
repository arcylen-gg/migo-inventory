<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use App\Globals\Cart;
use Crypt;
use Redirect;
use Request;
use View;
use DB;
class ShopMyCartController extends Shop
{
	public function if_loggedin()
    {
        if(!isset(Self::$customer_info->customer_id))
        {
            return Redirect::to("/mlm/login")->send();
        }
    }

    public function MyCart()
    {
    	// $this->if_loggedin();

        $data["page"] = "My Cart";
        $data["get_cart"] = Cart::get_cart($this->shop_info->shop_id);
        // dd($data["get_cart"]);
        return view("mycart", $data);
    }  
}