<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use DOMDocument;
use Config;
use DB;
use App\Globals\Mail_global;
use App\Models\Tbl_user;
use App\Models\Tbl_email_template;

class PartnersController extends Shop
{
    public function index()
    {
    	
        $data['page']='Partners';
        $data['company'] = DB::table('tbl_company_partners')->get();
        return view("partners_views",$data);
    }
}