<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use DB;
class ShopItemCheckoutController extends Shop
{
    

    public function item_checkout()
    {
        $data["page"] = "item_checkout";
        return view("item_checkout", $data);
    }  
}