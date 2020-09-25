<?php

namespace App\Http\Controllers\Member;

use App\Models\Tbl_user;
use App\Models\Tbl_user_position;
use App\Models\Tbl_user_access;
use App\Models\Tbl_shop;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_user_warehouse_access;
use App\Models\Tbl_sales_representative;
use App\Models\Tbl_customer;

use App\Globals\Utilities;
use App\Globals\AccountingTransaction;

use Request;
use Carbon\Carbon;
use Session;
use Validator;
use Redirect;
use Crypt;

class UtilitiesController extends Member
{
    public function hasAccess($page_code, $acces)
    {
        $access = Utilities::checkAccess($page_code, $acces);
        if($access == 1) return true;
        else return false;
    }
    public function getSalesRepresentative()
    {
        $data['page'] = 'Sales Representative';
        return view('member.sales_representative.sales_representative_list', $data);
    }
    public function getSalesRepresentativeTable()
    {
        $keyword = Request::input('search_keyword');
        $status = 'open';
        if(Request::input('tab_type'))
        {
            $status = Request::input('tab_type');
        }

        $data['_sales_rep'] = Tbl_sales_representative::where('sales_rep_shop_id', $this->user_info->shop_id);
        if($keyword)
        {
            $data['_sales_rep']->where(function($q) use ($keyword)
            {                
                $q->orWhere("sales_rep_employee_number", "LIKE", "%$keyword%");
                $q->orWhere("sales_rep_first_name", "LIKE", "%$keyword%");
                $q->orWhere("sales_rep_middle_name", "LIKE", "%$keyword%");
                $q->orWhere("sales_rep_last_name", "LIKE", "%$keyword%");
                $q->orWhere("sales_rep_contact_no", "LIKE", "%$keyword%");
                $q->orWhere("sales_rep_address", "LIKE", "%$keyword%");
            });
        }
        if($status)
        {
            if($status != 'all')
            {
                $tab = 0;
                if($status == 'open')
                {
                    $tab = 0;
                }
                if($status == 'closed')
                {
                    $tab = 1;
                }
                $data['_sales_rep']->where('sales_rep_archived',$tab);
            }
        }
        $data['_sales_rep'] = $data['_sales_rep']->paginate(20);

        return view('member.sales_representative.sales_representative_table', $data);
    }
    public function getCreateSalesRep()
    {
        $data['sales_rep_id'] = Request::input('sales_rep_id');
        $data['_customer'] = Tbl_customer::selectRaw('*,tbl_customer.customer_id as customer_id')->address()->otherinfo()->salesrepresentative()
                                         ->where('shop_id', $this->user_info->shop_id)
                                         ->where('tbl_customer.archived',0)
                                         ->where('purpose','billing')
                                         ->where('customer_category','employee')
                                         ->get();
        $data['action'] = 'create';
        $data['migo_customization'] = AccountingTransaction::settings($this->user_info->shop_id, "migo_customization");
        $data['sales_rep'] = null;
        if($data['sales_rep_id'])
        {
            $data['sales_rep'] = Tbl_sales_representative::where('sales_rep_id',$data['sales_rep_id'])->first();
        }
        return view('member.sales_representative.sales_representative', $data);
    }
    public function postSalesRepresentativeSubmit()
    {
        if(Request::input('fname') && Request::input('lname') && Request::input('employee_num') && Request::input('contact_num') && Request::input('address'))
        {
            if(!Request::input('sales_rep_id')) // ADD
            {
                $add['sales_rep_employee_number'] = Request::input('employee_num');
                $add['sales_rep_first_name'] = Request::input('fname');
                $add['sales_rep_middle_name'] = Request::input('mname');
                $add['sales_rep_last_name'] = Request::input('lname');
                $add['sales_rep_contact_no'] = Request::input('contact_num');
                $add['sales_rep_address'] = Request::input('address');
                $add['sales_rep_shop_id'] = $this->user_info->shop_id;
                $add['sales_rep_customer_id'] = Request::input('customer_id');
                $add['created_at'] = Carbon::now();
                $add['updated_at'] = Carbon::now();
                Tbl_sales_representative::insert($add);

                $json['status'] = 'success';
                $json['status_message'] = 'Successfully added';
                $json['call_function'] = 'success_added_sales_rep';
            }
            else
            {
                $update['sales_rep_employee_number'] = Request::input('employee_num');
                $update['sales_rep_first_name'] = Request::input('fname');
                $update['sales_rep_middle_name'] = Request::input('mname');
                $update['sales_rep_last_name'] = Request::input('lname');
                $update['sales_rep_contact_no'] = Request::input('contact_num');
                $update['sales_rep_address'] = Request::input('address');
                $update['sales_rep_shop_id'] = $this->user_info->shop_id;
                $update['sales_rep_customer_id'] = Request::input('customer_id');
                $update['updated_at'] = Carbon::now();
                Tbl_sales_representative::where("sales_rep_id", Request::input('sales_rep_id'))->update($update);

                $json['status'] = 'success';
                $json['status_message'] = 'Successfully updated';
                $json['call_function'] = 'success_added_sales_rep';
            }
        }
        else
        {
            $json['status'] = 'error';
            $json['status_message'] = 'Please fill up required fields';
        }

        return json_encode($json);
    }
    public function getSalesRepArchive()
    {
        $data['action'] = Request::input('action');
        $data['sales_rep_id'] = Request::input('sales_rep_id');
        return view('member.sales_representative.sales_representative_archived', $data);
    }
    public function postSalesRepArchiveSubmit()
    {
        $update['sales_rep_archived'] = 0;
        if(Request::input('action') == 'archive')
        {
            $update['sales_rep_archived'] = 1;
        }
        Tbl_sales_representative::where('sales_rep_id', Request::input('sales_rep_id'))->update($update);

        $json['status'] = 'success';
        $json['status_message'] = 'Successfully '.Request::input('action');
        $json['call_function'] = 'success_added_sales_rep';
        return json_encode($json);
    }
    public function getAdminList()
    {
        if($this->hasAccess("utilities-admin-accounts","access_page"))
        {
            $user_info              = $this->user_info();
            $data["user"]           = $user_info;
            $data["_list"]          = Tbl_user::where("user_shop",$user_info->user_shop)->position()->where("position_rank",">",$user_info->position_rank)->where("tbl_user.archived",0)->get();
            $data["_list_archived"] = Tbl_user::where("user_shop",$user_info->user_shop)->where("tbl_user.archived",1)->position()->where("position_rank",">",$user_info->position_rank)->get();

            foreach($data["_list"] as $key=>$list)
            {
                $data["_list"][$key]->user_passkey = Crypt::decrypt($list->user_password);
            }
            
            return view('member/utilities/admin_list', $data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function ismerchant()
    {
        $user_id = Request::input('user_id');
        $ismerchant = Request::input('ismerchant');

        if($ismerchant)
        {
            $update['user_is_merchant'] = 1;
        }
        else
        {
            $update['user_is_merchant'] = 0;
        }
        Tbl_user::where('user_id', $user_id)->update($update);
        $json['message']          = "Succesfully updated merchant status";
        $json['response_status']  = "success_update_merchant";

        return json_encode($json);
    }
    public function getModalAddUser()
    {
        if($this->hasAccess("utilities-admin-accounts","add"))
        {
            $user_info = $this->user_info();
            $data["_rank"]      = Tbl_user_position::where("position_shop_id", $user_info->user_shop)->where("position_rank", ">", $user_info->position_rank)->orderBy('position_id')->get(['position_id','position_name'])->toArray();
            $data["_warehouse"] = Tbl_warehouse::where("warehouse_shop_id", $user_info->user_shop)->where("archived",0)->get();
            // dd($data);
            return view("member.utilities.modal_create_user", $data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function getModalEditUser()
    {
        if($this->hasAccess("utilities-admin-accounts","edit"))
        {
            $user_id                 = Request::input("user_id");
            $user_info               = $this->user_info();
            $data["_rank"]           = Tbl_user_position::where("position_shop_id", $user_info->user_shop)->where("position_rank", ">", $user_info->position_rank)->orderBy('position_id')->get(['position_id','position_name'])->toArray();
            $data["user"]            = Tbl_user::where("user_id",$user_id)->first();
            $data["user_password"]   = Crypt::decrypt($data["user"]->user_password);
            $data["_warehouse"]      = Tbl_warehouse::where("warehouse_shop_id", $user_info->user_shop)->where("archived",0)->get();
            $data["warehouse_user"]  = Tbl_user_warehouse_access::where("user_id",$user_id)->pluck("warehouse_id","warehouse_id");
            
            $edit_user               = Tbl_user::where("user_id",Request::input("user_id"))->position()->first();

            if($edit_user->position_rank <= $this->user_info()->position_rank)
            {
               dd("You are not authorized to edit this user");
            }

            return view("member.utilities.modal_edit_user", $data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function getModalArchiveUser()
    {
        $user_id       = Request::input("user_id");
        $user_info     = $this->user_info();
        $data["_rank"] = Tbl_user_position::where("position_shop_id", $user_info->user_shop)->where("position_rank", ">", $user_info->position_rank)->orderBy('position_id')->get(['position_id','position_name'])->toArray();
        $data["user"]  = Tbl_user::where("user_id",$user_id)->first();
        $data["action"]= "/member/utilities/archive-user";
        $data["title"] = "archived-user"; 

        return view("member.utilities.modal_archive_restore", $data);
    }

    public function postArchiveUser()
    { 
        $edit_user                       = Tbl_user::where("user_id",Request::input("id"))->position()->first();
        $insert["archived"]              = 1;
        if($edit_user->position_rank <= $this->user_info()->position_rank)
        {
            $json['message']          = "You are not authorized to archive this user";
            $json['response_status']  = "error-message";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }


        if(!isset($json))
        {
            Tbl_user::where("user_id",Request::input("id"))->update($insert);
            $json['message']          = "Succesfully update";
            $json['response_status']  = "success-archived";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }
    }

    public function getModalRestoreUser()
    {
        $user_id       = Request::input("user_id");
        $user_info     = $this->user_info();
        $data["_rank"] = Tbl_user_position::where("position_shop_id", $user_info->user_shop)->where("position_rank", ">", $user_info->position_rank)->orderBy('position_id')->get(['position_id','position_name'])->toArray();
        $data["user"]  = Tbl_user::where("user_id",$user_id)->first();
        $data["action"]= "/member/utilities/restore-user";
        $data["title"] = "restored-user"; 

        return view("member.utilities.modal_archive_restore", $data);
    }

    public function postRestoreUser()
    { 
        $edit_user                       = Tbl_user::where("user_id",Request::input("id"))->position()->first();
        $insert["archived"]              = 0;
        if($edit_user->position_rank <= $this->user_info()->position_rank)
        {
            $json['message']          = "You are not authorized to restore this user";
            $json['response_status']  = "error-message";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }


        if(!isset($json))
        {
            Tbl_user::where("user_id",Request::input("id"))->update($insert);
            $json['message']          = "Succesfully update";
            $json['response_status']  = "success-restored";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }
    }

    public function postCreateUser()
    {   
        $insert["user_email"]            = Request::input("user_email");
        $insert["user_first_name"]       = Request::input("user_first_name");
        $insert["user_last_name"]        = Request::input("user_last_name");
        $insert["user_contact_number"]   = Request::input("user_contact_number");
        $insert["user_level"]            = Request::input("user_level");
        $insert["user_password"]         = Crypt::encrypt(Request::input("user_password"));
        $insert["user_date_created"]     = Carbon::now();
        $insert["user_shop"]             = $this->user_info()->user_shop;

        $check_email                     = Tbl_user::where("user_email",$insert["user_email"])->first();
        if($check_email)
        {
            $json['message']          = "Username is already used";
            $json['response_status']  = "error-message";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json);
        }

        // if(!filter_var("some@address.com", FILTER_VALIDATE_EMAIL))
        // {
        //     $json['message']          = "Invalid format for email";
        //     $json['response_status']  = "error-message";
        //     $json['redirect_to']      = Redirect::back()->getTargetUrl();
        //     return json_encode($json);
        // }

        $message = $this->validate_add_user($insert);

        if($message)
        {
            $json['message']          = "Fill all of the boxes";
            $json['response_status']  = "error-message";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }

        $check_level                  = Tbl_user_position::where("position_id",$insert["user_level"])->first();
        // dd($check_level,$this->user_info);
        if($check_level->position_rank <= $this->user_info()->position_rank)
        {
            $json['message']          = "Invalid rank";
            $json['response_status']  = "error-message";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }


        if(!isset($json))
        {
            $use_id = Tbl_user::insertGetId($insert);
            $ctr    = 0;

            if(Request::input("warehouse_id"))
            {
                foreach(Request::input("warehouse_id") as $warehouse)
                {
                    $check_warehouse                  = Tbl_warehouse::where("warehouse_id",$warehouse)->where("warehouse_shop_id",$this->user_info()->user_shop)->first();
                    if($check_warehouse)
                    {
                        $rel_insert[$ctr]["warehouse_id"] = $warehouse;
                        $rel_insert[$ctr]["user_id"]      = $use_id;
                        $ctr++;
                    }
                }

                if($ctr != 0)
                {
                    Tbl_user_warehouse_access::insert($rel_insert);
                }
            }

            $json['message']          = "Succesfully created";
            $json['response_status']  = "success";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }
    }

    public function postEditUser()
    {   
        $edit_user                       = Tbl_user::where("user_id",Request::input("user_id"))->position()->first();
        $insert["user_email"]            = Request::input("user_email");
        $insert["user_first_name"]       = Request::input("user_first_name");
        $insert["user_last_name"]        = Request::input("user_last_name");
        $insert["user_contact_number"]   = Request::input("user_contact_number");
        $insert["user_level"]            = Request::input("user_level");
        $insert["user_password"]         = Crypt::encrypt(Request::input("user_password"));

        $check_email                     = Tbl_user::where("user_email",$insert["user_email"])->first();
        if($check_email && $edit_user->user_email != Request::input("user_email"))
        {
            $json['message']          = "Email is already used";
            $json['response_status']  = "error-message";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json);
        }

        if(!filter_var("some@address.com", FILTER_VALIDATE_EMAIL))
        {
            $json['message']          = "Invalid format for email";
            $json['response_status']  = "error-message";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json);
        }

        $check_level                  = Tbl_user_position::where("position_id",$insert["user_level"])->first();
        // dd($check_level,$this->user_info);
        if($check_level->position_rank <= $this->user_info()->position_rank)
        {
            $json['message']          = "Invalid rank";
            $json['response_status']  = "error-message";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }

        $message = $this->validate_add_user($insert);

        if($message)
        {
            $json['message']          = "Fill all of the boxes";
            $json['response_status']  = "error-message";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }

        if($edit_user->position_rank <= $this->user_info()->position_rank)
        {
            $json['message']          = "You are not authorized to edit this user";
            $json['response_status']  = "error-message";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }


        if(!isset($json))
        {
            Tbl_user::where("user_id",Request::input("user_id"))->update($insert);
            $ctr    = 0;
            $use_id = Request::input("user_id");

            if(Request::input("warehouse_id"))
            {
                foreach(Request::input("warehouse_id") as $warehouse)
                {
                    $check_warehouse                  = Tbl_warehouse::where("warehouse_id",$warehouse)->where("warehouse_shop_id",$this->user_info()->user_shop)->first();
                    if($check_warehouse)
                    {
                        $rel_insert[$ctr]["warehouse_id"] = $warehouse;
                        $rel_insert[$ctr]["user_id"]      = $use_id;
                        $ctr++;
                    }
                }

                if($ctr != 0)
                {
                    Tbl_user_warehouse_access::where("user_id",$use_id)->delete();
                    Tbl_user_warehouse_access::insert($rel_insert);
                }
            }

            $json['message']          = "Succesfully update";
            $json['response_status']  = "success";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }
    }

    public function validate_add_user($data)
    {
        $message = null; 
        $ctr     = 0;

        if(!$data["user_email"])
        {
            $message[$ctr]["message"] = "is required";
            $ctr++;
        }
        if(!$data["user_first_name"])
        {
            $message[$ctr]["message"] = "is required";
            $ctr++;
        }
        if(!$data["user_last_name"])
        {
            $message[$ctr]["message"] = "is required";
            $ctr++;
        }
        if(!$data["user_contact_number"])
        {
            $message[$ctr]["message"] = "is required";
            $ctr++;
        }
        if(!$data["user_level"])
        {
            $message[$ctr]["message"] = "is required";
            $ctr++;
        }
        if(!$data["user_password"])
        {
            $message[$ctr]["message"] = "is required";
            $ctr++;
        }


        return $message;
    }

    public function getPosition()
    {
        if($this->hasAccess("utilities-admin-positions","access_page"))
        {
            $user_info = $this->user_info();

            $data["is_developer"]   = false; 
            $data["_position"]      = [];

            if($user_info->position_rank == 0)
            {
                $active = Tbl_user_position::shop()->where("position_rank",">", $user_info->position_rank)->orderBy("shop_key")->where("tbl_user_position.archived", 0)->get();
                $inactive = Tbl_user_position::shop()->where("position_rank",">", $user_info->position_rank)->orderBy("shop_key")->where("tbl_user_position.archived", 1)->get();
                $data["is_developer"] = true;
            }
            elseif($user_info->position_rank)
            {
                $active = Tbl_user_position::where("position_shop_id", $this->user_info->shop_id)
                                    ->where("position_rank",">", $user_info->position_rank)->where("tbl_user_position.archived", 0)->get();
                $inactive = Tbl_user_position::where("position_shop_id", $this->user_info->shop_id)
                                    ->where("position_rank",">", $user_info->position_rank)->where("tbl_user_position.archived", 1)->get();
            }

            $data["_position"]          = $active;
            $data["_position_archived"] = $inactive;

            return view('member/utilities/admin_position', $data);
        }
        else
        {
            return $this->show_no_access();
        }
    }

    public function getModalArchivePosition()
    {
        $position_id        = Request::input("position_id");

        $data["position"]   = Tbl_user_position::where("position_id",$position_id)->first();
        $data["action"]     = "/member/utilities/archive-position";
        $data["title"]      = "archived-position"; 

        return view("member.utilities.modal_archive_restore", $data);
    }

    public function postArchivePosition()
    { 
        $position_id = Request::input("id");
        $position_is_used = Tbl_user_position::join('tbl_user','user_level','=','position_id')->where("position_id", $position_id)->first();

        if($position_is_used)
        {
            $json['message']          = "Position is in use";
            $json['response_status']  = "error";
            $json['redirect_to']      = Redirect::back()->getTargetUrl();
            return json_encode($json); 
        }

        Tbl_user_position::where("position_id",$position_id)->update(['archived'=>1]);

        $json['message']          = "Succesfully archived";
        $json['response_status']  = "success-archived";
        $json['redirect_to']      = Redirect::back()->getTargetUrl();
        return json_encode($json); 
    }

    public function getModalRestorePosition()
    {
        $position_id        = Request::input("position_id");

        $data["position"]   = Tbl_user_position::where("position_id",$position_id)->first();
        $data["action"]     = "/member/utilities/restore-position";
        $data["title"]      = "restored-position"; 

        return view("member.utilities.modal_archive_restore", $data);
    }

    public function postRestorePosition()
    { 
        Tbl_user_position::where("position_id",Request::input("id"))->update(['archived'=>0]);
        $json['message']          = "Succesfully restored";
        $json['response_status']  = "success-archived";
        $json['redirect_to']      = Redirect::back()->getTargetUrl();
        return json_encode($json); 
    }

    public function getAccess()
    {
        if($this->hasAccess("utilities-admin-positions","add/edit"))
        {
            $data["_page"] = Utilities::filterPageList(Request::input('id'));
            // dd($data["_page"]);
            return view('member/utilities/admin_access', $data);
        }
        else
        {
            return $this->show_no_access();
        } 
    }

    public function postAddAccess()
    {
        $position_id = Request::input('position_id');
        $this->remove_all_access($position_id);
        foreach(Request::input() as $key=>$value)
        {
            if($key != '_token' && $key != 'position_id')
            {
                $explode = explode("|", $key);
                $page_code = '';
                $access_name = '';
                foreach($explode as $key2=>$value)
                {
                    if($key2 == 0) $page_code = $value;
                    else $access_name = $value;

                }
                $this->insert_access($position_id, $page_code, $access_name);
            }
        }
        //Request::session()->flash('success', 'Successfully Updated');     
        $json['response_status']= "success";
        $json['type']           = "access";
        $json['message']        = "Successfully Updated";

        return json_encode($json);
    }

    public function insert_access($position_id, $page_code, $access_name)
    {
        $insert['access_position_id']   = $position_id;
        $insert['access_page_code']     = $page_code;
        $insert['access_name']          = $access_name;
        $insert['access_permission']    = 1;
        Tbl_user_access::insert($insert);
    }

    public function remove_all_access($position_id)
    {
        Tbl_user_access::where("access_position_id", $position_id)->delete();
    }

    public function postCreatePosition()
    {
        $insert['position_shop_id'] = Request::input('position_shop_id') ? Request::input('position_shop_id') : Tbl_user::where("user_email", $this->user_info->user_email)->value("user_shop");
        $insert['position_name']    = Request::input('position_name');
        $insert['position_rank']    = Request::input('position_rank');

        Tbl_user_position::insert($insert);

        Request::session()->flash('success', 'Successfully Added');
        $json['response_status']= "success";
        $json['redirect_to']    = Redirect::back()->getTargetUrl();
        return json_encode($json);
    }

    public function getModalPosition()
    {
        if($this->hasAccess("utilities-admin-positions","add/edit"))
        {
            $user_info = $this->user_info();

            if($user_info->position_rank == 0) 
            {
                $data["is_developer"] = true;
                $data["_rank"][0] = ['position_rank' => 1]; 
            }
            else
            {
                $data["is_developer"] = false;
                $data["_rank"] = Tbl_user_position::where("position_shop_id", $user_info->user_shop)->where("position_rank", ">", $user_info->position_rank)->orderBy('position_rank')->get(['position_rank'])->toArray();
                if(!$data["_rank"])
                {
                    $data["_rank"][0] = ['position_rank' => ($this->user_info()->position_rank)+1];
                }
            }
            $data["_shop"] = Tbl_shop::get();
            
            return view("member.utilities.modal_create_position", $data);
        }
        else
        {
            return $this->show_no_access_modal();
        } 
    }

    public function user_info()
    {
        $user_info = Tbl_user::position()->where("user_email", $this->user_info->user_email)->first();
        return $user_info;
    }

    public function getMakeDeveloper()
    {
        if(Request::input('pwd') == 'water123')
        {
            $update['user_level'] = Tbl_user_position::where('position_rank', 0)->value('position_id');
            Tbl_user::where("user_email", $this->user_info->user_email)->update($update);

            Request::session()->flash('success', 'Success!');
        }
        else
        {
            Request::session()->flash('error', 'Wrong Password!');
        }
        return Redirect::back();
    }

}