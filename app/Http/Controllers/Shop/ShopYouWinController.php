<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;

class ShopYouWinController extends Shop
{
    public function index()
    {
        $data["page"] = "You Win";
        return view("youwin", $data);
    }
}