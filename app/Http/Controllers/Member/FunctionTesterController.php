<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Globals\Cart;

class FunctionTesterController extends Member
{
	public function index()
	{
		// $data = Cart::add_to_cart(1,1,1);
		$data = Cart::customer_get_settings(1);
	}
	public function clear_all()
	{
			Cart::clear_all(1);
	}
	public function clear_one()
	{
			Cart::delete_product(1,1);
	}
	public function get_cart()
	{
		    dd(Cart::get_cart(1));
	}
}