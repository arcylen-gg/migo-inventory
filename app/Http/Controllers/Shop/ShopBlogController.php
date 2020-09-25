<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;

class ShopBlogController extends Shop
{
    public function index()
    {
        $data["page"] = "Blog";
        return view("blog", $data);
    }
}