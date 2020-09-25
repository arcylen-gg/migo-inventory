<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;

class ShopLegalitiesController extends Shop
{
    public function index()
    {
        $data["page"] = "Legalities";
        return view("legalities", $data);
    }
}