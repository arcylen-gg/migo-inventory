<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;

class ShopTestimonyController extends Shop
{
    public function index()
    {
        $data["page"] = "Testimony";
        return view("testimony", $data);
    }
}