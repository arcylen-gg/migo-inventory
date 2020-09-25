<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use Session;
use App\Globals\Ec_wishlist;

class ShopWishlistController extends Shop
{
	public static $customer_id;
	public function checkif_login()
    {
        if(Session::get('mlm_member') != null)
        {
            $session = Session::get('mlm_member');
            Self::$customer_id = $session['customer_info']->customer_id;
        }
        else
        {
            return Redirect::to("/")->send();
        }
    }
    public function add($id)
    {
    	$this->checkif_login();
        Ec_wishlist::addProduct($id, Self::$customer_id, $this->shop_info->shop_id);

        return Redirect::to('/account/wishlist');
    }
    public function remove($id)
    {
    	$this->checkif_login();
    	Ec_wishlist::removeProduct($id, $this->shop_info->shop_id);

    	return Redirect::to('/account/wishlist');
    }
}