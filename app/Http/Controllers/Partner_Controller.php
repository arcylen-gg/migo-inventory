<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tbl_partners;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class Partner_Controller extends Controller
{
    
    public function display()
    {
        $data["_company_info"]  = Tbl_partners::get();
        $data['locationList'] = Tbl_partners::select('company_location')->distinct()->get();
        
        return view("PartnerCompany", $data);
    }


}
