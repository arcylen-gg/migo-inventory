<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use DB;
class ShopItemPaymentController extends Shop
{
    

    public function item_payment()
    {
        $data["page"] = "item_payment";
        return view("item_payment", $data);
    }

    public function payment_success()
    {
        $data["page"] = "payment_success";
        return view("payment_success", $data);
    }

}