<?php

namespace App\Http\Controllers\Member;
use Request;
use stdClass;
use Redirect;
use Excel;
use DB;
use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_bank_convertion;

class PayrollBankingController extends Member
{
     public function shop_id()
     {
     	return $this->user_info->shop_id;
     }
     public function index($payroll_period_company_id)
     {
     	$data["payroll_period_company_id"] = $payroll_period_company_id;
     	$data["_employee"] = Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $payroll_period_company_id)->basic()->get();
 		$data = Self::clean($data);
    	return view("member.payroll2.payroll_banking", $data);
     }
     public static function clean($data)
     {
    	foreach($data["_employee"] as $key => $employee)
    	{
    		/* REMOVE EMPLOYEE WITH NO INCOME */
    		if($employee->net_pay < 0)
    		{
    			unset($data["_employee"][$key]);
    		}

    		if($employee->payroll_employee_atm_number == "")
    		{
    			unset($data["_employee"][$key]);
    		}
    	}
     	return $data;
     }
     public function download($payroll_period_company_id)
     {
        if (isJson(Request::input("employee"))) 
        {
            $employee = json_decode(Request::input("employee"));
        }
        else
        {
            $employee = [];
        }

     	$data["payroll_period_company_id"] = $payroll_period_company_id;
     	$data["payroll_period"] = Tbl_payroll_period_company::getcompanydetails($payroll_period_company_id)->first();
        $data["_employee"] = Tbl_payroll_time_keeping_approved::whereIn("employee_id", $employee)->where("payroll_period_company_id", $data["payroll_period_company_id"])->orderBy("net_pay", "desc")->basic()->get();


        if (Request::input("xls")) 
        {
                Self::download_xls($data);
            
        }
        else
        {
            switch ($data["payroll_period"]->payroll_bank_convertion_id)
            {
                case 1: //BDO
                    Self::download_bdo($data);
                break;
                case 2: //METROBANK
                    Self::download_metrobank($data);
                break;
                case 3: //EQUICOM
                    Self::download_equicom($data);
                break;
                case 4: //CHINABANK
                    Self::download_chinabank($data);
                break;
                default:
                    dd("Please set company Bank.");
                break;
            }
        }
     }


     public static function download_xls($data)
     {
        $data = Self::clean($data);

        $company_code   = $data["payroll_period"]->payroll_company_code;
        $upload_month   = date("m");
        $upload_day     = date("d");
        $upload_year    = date("y");
        $batch = "01";
        $data["payroll_period"]->payroll_company_account_no = DB::table("tbl_payroll_company")->where("payroll_company_id", $data["payroll_period"]->payroll_company_id)->first()->payroll_company_account_no;
        
        // S3N
        $filename = strtoupper($company_code) . $upload_month . $upload_day . $upload_year . $batch;

        $payroll_bank_convertion_id = $data["payroll_period"]->payroll_company_bank;
        $payroll_bank_name          = Tbl_payroll_bank_convertion::where('payroll_bank_convertion_id',$payroll_bank_convertion_id)->value('bank_name');
        
        if($payroll_bank_name == 'Metro Bank Single')
        {
                    Excel::create($filename, function($excel) use ($data)
                    {
                        $excel->sheet('Data', function($sheet) use ($data)
                        {
                            $sheet->loadView('member.payroll2.bank_template.metrobanksingle', $data);
                        });
                    })->download('xls');
        }
        else
        {
                    Excel::create($filename, function($excel) use ($data)
                    {
                        $excel->sheet('Data', function($sheet) use ($data)
                        {
                            $sheet->loadView('member.payroll2.bank_template.default', $data);
                        });
                    })->download('xls');
        }


        $data["_employee"] = Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $data["payroll_period_company_id"])->orderBy("net_pay", "desc")->basic()->get();
        $data = Self::clean($data);
     }


     public static function download_bdo($data)
     {
        $data = Self::clean($data);

        $company_code   = $data["payroll_period"]->payroll_company_code;
        $upload_month   = date("m");
        $upload_day     = date("d");
        $upload_year    = date("y");
        $batch = "01";

        //S3N
        $filename = strtoupper($company_code) . $upload_month . $upload_day . $upload_year . $batch . '.txt';
        $content = "";

        foreach($data["_employee"] as $employee)
        {
            $content .=  $employee->payroll_employee_atm_number . "\t" . number_format($employee->net_pay, 2) . "\r\n";
        }

        $f = fopen($filename, 'w');
        fwrite($f, $content);
        fclose($f);

        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Length: ". filesize("$filename").";");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/octet-stream; "); 
        header("Content-Transfer-Encoding: binary");

        readfile($filename);
        unlink($filename);
     }
     public static function download_metrobank($data)
     {
        dd("Under Construction");
     }
     public static function download_equicom($data)
     {
        dd("Under Construction");
     }
     public static function download_chinabank($data)
     {
        dd("Under Construction");
     }
}