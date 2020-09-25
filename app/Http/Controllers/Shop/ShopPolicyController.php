<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;

class ShopPolicyController extends Shop
{
    public function index()
    {
        $data["page"] = "Policy";
        return view("policy", $data);
    }
}