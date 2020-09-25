<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;

class ShopPaymentController extends Shop
{
    public function index()
    {
        $data["page"] = "Payment";
        return view("payment", $data);
    }
}