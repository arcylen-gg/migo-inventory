<?php

namespace App\Http\Controllers\Member;

use Request;
use DB;
use Crypt;
use Carbon\Carbon;
use Session;
use App\Http\Controllers\Controller;
use App\Models\Tbl_country;
use App\Models\Tbl_customer;
use App\Models\Tbl_user;
use App\Models\Tbl_customer_search;
use App\Models\Tbl_product;
use App\Models\Tbl_variant;
use App\Models\Tbl_product_vendor;
use App\Models\Tbl_order;
use App\Models\Tbl_order_item;

class CheckOutController extends Member
{
    public function index()
    {
        return view('member.order.draft.index');
    }
}
