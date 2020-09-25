<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;

class Vendor_ListController extends Member
{
    public function index()
    {
    	dd("hello");
        $data["page"] = "Vendor List";
        return view('member.vendor_list.vendor_list');
    }
}