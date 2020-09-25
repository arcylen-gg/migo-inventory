<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_company extends Model
{
    protected $table = 'tbl_payroll_company';
	protected $primaryKey = "payroll_company_id";
    public $timestamps = false;

    /* REFERENCE COLUMN NAME */
    // [PRIMARY KEY]    payroll_company_id
    // [INTEGER]        payroll_parent_company_id
    // [VARCHAR]        payroll_company_name
    // [TEXT]           payroll_company_address
    // [VARCHAR]        payroll_company_contact
    // [VARCHAR]        payroll_company_email
    // [VARCHAR]        payroll_company_nature_of_business
    // [INTEGER]        payroll_company_rdo
    // [DATE]           payroll_company_date_started
    // [VARCHAR]        payroll_company_tin
    // [VARCHAR]        payroll_company_sss
    // [VARCHAR]        payroll_company_philhealth
    // [VARCHAR]        payroll_company_pagibig
    // [VARCHAR]        payroll_company_logo
    // [TINYINTEGER]    payroll_company_archived
    // [INTEGER]        shop_id
    // [VARCHAR]        payroll_company_code
    

    public function scopeselcompany($query,$shop_id = 0, $archived = 0)
    {
        $query->leftjoin('tbl_payroll_rdo','tbl_payroll_rdo.payroll_rdo_id','=','tbl_payroll_company.payroll_company_rdo')
              ->where('shop_id', $shop_id)
              ->where('payroll_company_archived',$archived);

        return $query;
    }

    public function scopeselcompanybyid($query,$payroll_company_id = 0, $archived = 0)
    {
        $query->leftjoin('tbl_payroll_rdo','tbl_payroll_rdo.payroll_rdo_id','=','tbl_payroll_company.payroll_company_rdo')
              ->where('payroll_company_id', $payroll_company_id)
              ->where('payroll_company_archived',$archived);

        return $query;
    }

    public function scopeselcompanybranch($query,$payroll_company_id = 0, $archived = 0)
    {
        $query->leftjoin('tbl_payroll_rdo','tbl_payroll_rdo.payroll_rdo_id','=','tbl_payroll_company.payroll_company_rdo')
              ->whereIn('payroll_company_id', $payroll_company_id)
              ->where('payroll_company_archived',$archived);

        return $query;
    }


    public function scopeselbycode($query, $shop_id = 0, $payroll_company_code = '')
    {
        $query->where('shop_id', $shop_id)->where('payroll_company_code', $payroll_company_code);
        return $query;
    }

    public function scopesellost($query, $shop_id = 0, $archived = 0)
    {
        $lost = 0;
        if($archived == 0)
        {
            $lost = 1;
        }
        $query->join('tbl_payroll_company as parent','parent.payroll_company_id','=','tbl_payroll_company.payroll_company_id')
             ->leftjoin('tbl_payroll_rdo','tbl_payroll_rdo.payroll_rdo_id','=','tbl_payroll_company.payroll_company_rdo')
             ->where('tbl_payroll_company.shop_id',$shop_id)
             ->where('tbl_payroll_company.payroll_company_archived',$archived)
             ->where('parent.payroll_company_archived',$lost)
             ->select('tbl_payroll_company.*','parent.payroll_company_archived as parent_archived');

        return $query;
    }


    public function scopegetbyperiod($query, $payroll_period_company_id = 0)
    {
        $query->join('tbl_payroll_period_company','tbl_payroll_period_company.payroll_company_id','=','tbl_payroll_company.payroll_company_id')
              ->where('tbl_payroll_period_company.payroll_period_company_id', $payroll_period_company_id);

        return $query;
    }

    public function scopecompanydetails($query,$shop_id = 0)
    {
        $query->join('tbl_payroll_rdo','tbl_payroll_rdo.payroll_rdo_id','=','tbl_payroll_company.payroll_company_rdo')
              ->join('tbl_payroll_bank_convertion','tbl_payroll_bank_convertion.payroll_bank_convertion_id','=','tbl_payroll_company.payroll_company_bank')
              ->where('shop_id', $shop_id);

        return $query;
    }
}
