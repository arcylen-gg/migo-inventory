<?php
namespace App\Http\Controllers\Reward;
use App\Http\Controllers\Controller;
use App\Models\Tbl_shop;

class RewardController extends Controller
{
	public $shop_id = 1;
	public $shop_info;
	
	public function __construct()
	{
		$this->shop_info = Tbl_shop::where("shop_id", $this->shop_id)->first();
	}
	public function index()
	{
		
	}
}