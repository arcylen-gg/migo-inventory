<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;

class ShopHowController extends Shop
{
    public function index()
    {
        $data["page"] = "How to Order";
        return view("how", $data);
    }
}