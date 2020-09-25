<?php
namespace App\Globals;
use DB;

class Currency
{
	public static function format($amount)
	{
		return "PHP " . number_format($amount, 2);
	}
}