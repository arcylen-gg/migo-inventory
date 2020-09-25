<?php
namespace App\Http\Controllers\Reward;
use App\Http\Controllers\Controller;

class RewardTestController extends RewardController
{
	public function index()
	{
		$data["page"] = "TEST";
		$data["shop_info"] = $this->shop_info;
		dd($data);
		return view("member.reward.test", $data);
	}
}