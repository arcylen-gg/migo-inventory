<?php
namespace App\Http\Controllers;
use App\Models\Tbl_shop;
use App\Models\Tbl_applicant;
use View;
use Validator;

class ExamController extends Controller
{
    public $shop_info;

    public function __construct()
    {
        $domain = get_domain();
        $data['lead'] = null;
        $data['lead_code'] = null;
        $data['customer_info'] = null;
        $check_domain = Tbl_shop::where("shop_domain", $domain)->first();

        if(hasSubdomain())
        {
            $url = $_SERVER['HTTP_HOST'];
            $host = explode('.', $url);
            $subdomains = array_slice($host, 0, count($host) - 2 );
            $subdomain = $subdomains[0];
            $this->shop_info = $shop_info = Tbl_shop::where("shop_key", $subdomain)->first();
        }
        elseif($check_domain)
        {
            $this->shop_info = $check_domain;
        }
        else
        {
            abort(404);
        }

        View::share("shop_info", $this->shop_info);
    }
    public function getIndex()
    {
    	$data["page"] = "Exam";
        return view('exam.exam', $data);
    }
    public function getRegister()
    {
    	$data["page"] = "Exam";
        return view('exam.exam_register', $data);
    }

    public function postRegister()
    {
        $condition["first_name"]        = array("required");
        $condition["last_name"]         = array("required");
        $condition["email"]             = array("required","email","unique:tbl_applicant,email");
        $condition["contact"]           = array("required","unique:tbl_applicant,contact");
        $condition["gender"]            = array("required");
        $condition["complete_address"]  = array("required");
        $validator                      = Validator::make(request()->all(), $condition);

        if ($validator->fails())
        {
            $errors                 = $validator->errors();
            $return["status"]       = "error";
            $return["title"]        = "Validation Error";
            $return["message"]      = $errors->first();
        }
        else
        {
            $insert                 = request()->input();
            $insert["shop_id"]      = $this->shop_info->shop_id;

            unset($insert["_token"]);

            Tbl_applicant::insert($insert);
            $return["status"]       = "success";
            $return["load_page"]    = "/exam/question";
        }

        echo json_encode($return);
    }
    public function getQuestion()
    {
        $data["page"] = "Exam";
        return view('exam.exam_question', $data);
    }
}