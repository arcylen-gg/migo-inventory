<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_employee;
use App\Models\Tbl_position;
use App\Globals\Employee;
use Validator;
use Carbon\Carbon;
use App\Globals\Ec_wishlist;
use Crypt;
class WishlistController extends Member
{
    public function list()
    {
        $data["product"] = $this->products();
        $data["customer"] = $this->customers();

        return view("member.wishlist.list", $data);
    }
    public function products()
    {
    	$data["_product"] = Ec_wishlist::getProductCount($this->user_info->shop_id);
       
    	return view("member.wishlist.products", $data);
    }
     public function customers()
    {
    	$data["_customer"] = Ec_wishlist::getCustomerCount($this->user_info->shop_id);
        
    	return view("member.wishlist.customers", $data);
    }
}
