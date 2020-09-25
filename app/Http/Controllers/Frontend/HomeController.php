<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
class HomeController extends Controller
{
	public function index()
	{
		return view('frontend.home.home');
	}
	public function pricing()
	{
		return view('frontend.pricing.pricing');
	}
	public function support()
	{
		return view('frontend.support.support');
	}
	public function createAccount(){
		
	}
}