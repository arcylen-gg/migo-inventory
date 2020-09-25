<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;

class Product_ServiceListController extends Member
{
    public function index()
    {
        $data["page"] = "Vendor List";
        return view('member.product_service.product_service_list');
    }
}