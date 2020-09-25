<?php

namespace App\Http\Controllers\Member;

use Request;
use Session;
use App\Http\Controllers\Controller;
use App\Models\Tbl_audit_trail;
use App\Models\Tbl_user;
use DB;

use App\Globals\AuditTrail;
use App\Globals\Utilities;
class AuditTrailController extends Member
{
    public function hasAccess($page_code, $acces)
    {
        $access = Utilities::checkAccess($page_code, $acces);
        if($access == 1) return true;
        else return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $data["_column"] = array(
                ' '                     => "*", 
                'fullname'              => "User Full Name", 
                'remarks'               => "Transaction", 
            );

        if($this->hasAccess("utilities-audit","access_page"))
        {   
          
            return view("member.audit_trail.audit_trail",$data);
        }
        else
        {
            return $this->show_no_access();
        }

    }


    public function get_list()
    {
        $date_from  = Request::input('date_from');
        $date_to    = Request::input('date_to');
        $col        = Request::input('col');
        $keyword    = Request::input('keyword');
        //dd($date_to);
        if ($keyword == '')
        {
            $data["_audit"] = AuditTrail::getAudit_data();  
            // $data["_audit"] = Tbl_audit_trail::
            //                 where("audit_shop_id",AuditTrail::getShopId())
            //                 ->orderBy("tbl_audit_trail.audit_trail_id","DESC")
            //                 ->paginate(15);  
            //                 // dd($data);
        } 
        else
        {
            if ($date_from != '' && $date_to != '')
            {
                $data["_audit"] = AuditTrail::getSearchAuditData($col, $keyword, $date_from, $date_to);
                //dd('sample');
            }
                else
            {                 
                $data["_audit"] = AuditTrail::getSearchAuditData($col, $keyword);    
                //dd('pasok');
            }
        }

        return view("member.audit_trail.audit_list", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
