<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;
use Carbon\Carbon;

use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_customer;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_mlm_transfer_slot_log;
use App\Models\Tbl_item_code;
use App\Models\Tbl_item_code_transfer_log;
use App\Models\Tbl_membership_code_transfer_log;
use App\Models\Tbl_warehouse_inventory_record_log;

use App\Globals\Mlm_compute;
use App\Globals\Mlm_member;
use App\Globals\Warehouse2;
use App\Globals\Item_code;
use App\Globals\Mlm_plan;
use App\Globals\MLM2;
use App\Globals\Item;
class MlmSlotsController extends Mlm
{
    public function index()
    {
            $data = [];
            $check_if_enabled = Tbl_mlm_plan_setting::where("shop_id",Self::$shop_id)->where("plan_settings_upgrade_slot",1)->first();
            if($check_if_enabled)
            {   
                $data["enabled_upgrade_slot"] = 1;
            }
            else
            {
                $data["enabled_upgrade_slot"] = 0;
            }
            $data['all_slots_p']       = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->membership()->paginate(20);
            $data['active']            = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->where('slot_defaul', 1)->first();
            $data['_code']             = Tbl_membership_code::where('customer_id', Self::$customer_id)->where('used', 0)->package()->membership()->get();
            $data["all_slots_show"]    = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->membership()->get();
            $data["_item_code"]        = Tbl_item_code::where("customer_id",Self::$customer_id)->where("used",0)->where("blocked",0)->where("archived",0)->get();
            return view('mlm.slots.index', $data);
    }
    public function set_nickname()
    {
        // return $_POST;
        if(isset($_POST['active_slot']))
        {
            if(isset($_POST['slot_nickname']))
            {
                $customer_id = Self::$customer_id;
                $slot_id = $_POST['active_slot'];
                $nickname = $_POST['slot_nickname'];
                $slot = Tbl_mlm_slot::where('slot_owner', $customer_id)->where('slot_no', $slot_id)->first();
                if(isset($slot->slot_id))
                {
                    if(strlen($nickname) >= 6)
                    {
                        $user_count = Tbl_mlm_slot::where('slot_no', $nickname)->where('slot_owner', '!=', $customer_id)->count();
                        $user_count_2 = Tbl_mlm_slot::where('slot_nick_name', $nickname)->where('slot_owner', '!=', $customer_id)->count();
                        $user_count_3 = Tbl_customer::where('mlm_username', $nickname)->count();
                        $all = $user_count + $user_count_2 + $user_count_3;
                        if($all == 0)
                        {
                            $update['slot_defaul'] = 0;
                            $update['slot_nick_name'] = null;
                            Tbl_mlm_slot::where('slot_owner', $customer_id)->update($update);

                            $update_new['slot_defaul'] = 1;
                            $update_new['slot_nick_name'] = $nickname;
                            Tbl_mlm_slot::where('slot_owner', $slot->slot_owner)->where('slot_id', $slot->slot_id)->update($update_new);
                            $data['message'] ='Slot Nickname/Default slot Changed!';
                            $data['status'] = 'success';
                        }
                        else
                        {
                            $data['message'] ='Nickname Already Taken.';
                            $data['status'] = 'warning';
                        } 
                        
                    }
                    else
                    {
                        $data['message'] ='Nickname Length Must Be Greater Than 6.';
                        $data['status'] = 'warning';
                    }
                }
                else
                {
                    $data['message'] ='Invalid Slot';
                    $data['status'] = 'warning';
                }
            }
            else
            {
                $data['message'] ='Nickname field is required';
                $data['status'] = 'warning';
            }
        }
        else
        {
            $data['message'] ='Slot field is required';
            $data['status'] = 'warning';

        }
        return json_encode($data);
    }
    public function upgrade_slot($id)
    {
        $check_if_enabled = Tbl_mlm_plan_setting::where("shop_id",Self::$shop_id)->where("plan_settings_upgrade_slot",1)->first();
        if($check_if_enabled)
        {   
            $data["id"] = $id;
            $slot       = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->where("slot_id",$id)->first();
            if(!$slot)
            {
                dd("Wrong owner.");
            }

            $current_membership = Tbl_membership::where("membership_id",$slot->slot_membership)->first();
            $higher_membership  = Tbl_membership::where("shop_id",Self::$shop_id)->where("membership_price",">",$current_membership->membership_price)->pluck("membership_id");
            
            $data["membership_code"]       = Tbl_membership_code::package()
                                                                ->whereIn("membership_id",$higher_membership)
                                                                ->where("used",0)->where("blocked",0)
                                                                ->where("archived",0)
                                                                ->where("customer_id",Self::$customer_id)
                                                                ->get();

            $data["membership_code_count"] = Tbl_membership_code::package()
                                                                ->whereIn("membership_id",$higher_membership)
                                                                ->where("used",0)->where("blocked",0)
                                                                ->where("archived",0)
                                                                ->where("customer_id",Self::$customer_id)
                                                                ->count();

            return view('mlm.slots.upgrade_slot',$data);
        }
        else
        {
            dd("Upgrade slot settings is disabled");
        }
    }

    public function upgrade_slot_post($id)
    {
        $error      = null;
        $slot       = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->where("slot_id",$id)->first();
        if(!$slot)
        {
            $message["message"] = "Wrong owner.";
        }

        $membership_code_id = Request::input("membership_code_id");
        $current_membership = Tbl_membership::where("membership_id",$slot->slot_membership)->first();
        $higher_membership  = Tbl_membership::where("shop_id",Self::$shop_id)->where("membership_price",">",$current_membership->membership_price)->pluck("membership_id");
        
        $membership_code    = Tbl_membership_code::package()
                                                 ->where("tbl_membership_code.used",0)
                                                 ->where("tbl_membership_code.blocked",0)
                                                 ->where("tbl_membership_code.archived",0)
                                                 ->whereIn("membership_id",$higher_membership)
                                                 ->where("membership_code_id",$membership_code_id)
                                                 ->where("customer_id",Self::$customer_id)
                                                 ->first();

        if(!$membership_code)
        {
            $message["message"] = "This code is not available.";
        }

        if($error == null)
        {
            $membership                = Tbl_membership::where("membership_id",$membership_code->membership_id)->first();

            $update["used"]            = 1;
            $update["date_used"]       = Carbon::now();
            $update["used_on_upgrade"] = 1;
            $update["upgrade_slot"]    = $id;
            $update["slot_id"]         = $id;
            Tbl_membership_code::where("membership_code_id",$membership_code_id)->update($update);

            $update_slot["upgraded"]   = 1;
            $update_slot["upgrade_from_membership"] = $slot->slot_membership;
            $update_slot["slot_membership"] = $membership->membership_id;
            Tbl_mlm_slot::where("slot_id",$id)->update($update_slot);
            Mlm_compute::entry($id,1);

            $message["status"]           = "success-upgrade";
            $message["message"]          = "Sucessfully upgraded";
            $message["membership_name"]  = $membership->membership_name;
            $message["slot_id"]          = $id;
        }
        return json_encode($message);
    } 
    public function add_slot_modal()
    {
        return Mlm_member::add_slot_form(Self::$customer_id);
    } 
    public function manual_add_slot_modal()
    {
        return Mlm_member::manual_add_slot_form(Self::$customer_id,Request::input("membership_id"),Self::$shop_id);
    }
    public function check_add()
    {
        $check_membership  = Tbl_membership_code::where("membership_code_id",Request::input("membership_code_id"))
                                                ->where("blocked",0)
                                                ->where("archived",0)
                                                ->where("used",0)
                                                ->where("customer_id",Self::$customer_id)
                                                ->first();
        if($check_membership)
        {
            $data["status"]    = "sucess-slot";
            $data["encrypted"] = Crypt::encrypt(Request::input("membership_code_id"));
        }
        else
        {
            $data["message"]   = "Membership code doesn't exists";
        }

        return json_encode($data);
    }

    public function manual_add_slot()
    {
        
        return view('mlm.slots.manual_add_slot');
    }

    public function manual_add_slot_post()
    {
        $check_membership  = Tbl_membership_code::where("membership_code_id",Request::input("membership_code_id"))
                                                ->where("membership_activation_code",Request::input("membership_activation_code"))
                                                ->where("shop_id",Self::$shop_id)
                                                ->first();

        if(!$check_membership)
        {
            $message["message"]          = "This code is not available.";
        }
        else
        {
            if($check_membership->used == 1)
            {
                $message["message"]          = "This code is already used.";
            }
            else if($check_membership->blocked == 1)
            {
                $message["message"]          = "This code is blocked.";
            }
            else if($check_membership->archived == 1)
            {
                $message["message"]          = "This code is not available.";
            }
            else
            {
                $message["status"]           = "success-manual";
                $message["encrypted"]        = Crypt::encrypt(Request::input("membership_code_id"));
            }
        }

        return json_encode($message);
    }

    public function before_transfer_slot()
    {
        $check_slot = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->where('slot_id', Request::input("slot_id"))->first();
        if($check_slot)
        {
            $data["encrypted"] = Crypt::encrypt(Request::input("slot_id"));
            $data["status"]    = "success_before_transfer_slot"; 
        }
        else
        {
            $data["message"] = "Slot doesn't exists.";
        }

        return json_encode($data);
    }

    public function transfer_slot()
    {
        $slot_id            = Crypt::decrypt(Request::input("slot_id"));
        $data["encrypted"]  = Request::input("slot_id");
        $data["slot"]       = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->where("slot_id",$slot_id)->first();
        return view('mlm.slots.transfer_slot',$data);
    }

    public function transfer_slot_post()
    {
        $slot_id            = Crypt::decrypt(Request::input("slot_id"));
        $check_slot         = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->where('slot_id', $slot_id)->first();
        if($check_slot)
        {
            $transfer_to_customer = Tbl_customer::where("shop_id",Self::$shop_id)->where("mlm_username",Request::input("mlm_username"))->first();
            if($transfer_to_customer)
            {
                if($transfer_to_customer->customer_id != Self::$customer_id)
                {
                    $account        = Tbl_customer::where("customer_id",Self::$customer_id)->first();
                    $password       = Crypt::decrypt($account->password);
                    $check_pass     = Request::input("password");
                    if($password == $check_pass)
                    {
                        $insert_log["slot_transfer_by"]   = Self::$customer_id;
                        $insert_log["slot_transfer_to"]   = $transfer_to_customer->customer_id; 
                        $insert_log["slot_id"]            = $slot_id; 
                        $insert_log["transfer_slot_date"] = Carbon::now(); 
                        Tbl_mlm_transfer_slot_log::insert($insert_log);


                        $update_slot["slot_owner"] = $transfer_to_customer->customer_id;
                        Tbl_mlm_slot::where("slot_id",$slot_id)->update($update_slot);
                        $data["status"] = "success_transfer_slot";
                    }
                    else
                    {
                        $data["message"] = "Password mismatch";
                    }
                }
                else
                {
                    $data["message"] = "Cannot transfer the slot to yourself.";
                }
            }
            else
            {
                $data["message"] = "Customer doesn't exist";
            }

        }
        else
        {
            $data["message"] = "Slot doesn't exists.";
        }

        return json_encode($data);
    }

    public function item_code()
    {
        $item_code_id   = Request::input("item_code_id");
        $item           = Tbl_item_code::where("item_code_id",$item_code_id)->first();
        if($item)
        {
            if($item->used == 1)
            {
                $data["message"] = "Code already used";
            }
            else if($item->blocked == 1)
            {
                $data["message"] = "Code is blocked";
            }
            else if($item->archived == 1)
            {
                $data["message"] = "Code doesn't exists";
            }
            else if($item->customer_id != Self::$customer_id)
            {
                $data["message"] = "Code not found.";
            }
            else
            {
                $data["item"]  = $item;
                $data["_slot"] = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->membership()->get();
            }
        }
        else
        {
            $data["message"] = "Code does not exists";
        }



        if(isset($data["message"]))
        {
            return json_encode($data["message"]);
        }
        else
        {
            $data["_plan"] = Mlm_plan::get_all_active_plan_repurchase(Self::$shop_id);
            // dd($data);
            return view('mlm.slots.item_code',$data);
        }
    }

    public function item_code_post()
    {
        $item_activation_code = Request::input("item_activation_code");
        $slot_id              = Request::input("slot_id");

        $slot                 = Tbl_mlm_slot::where("slot_id",$slot_id)->where("slot_owner",Self::$customer_id)->first();
        $item                 = Tbl_item_code::where("item_activation_code",$item_activation_code)->first();
        if($slot)
        {
            if($item)
            {
                if($item->used == 1)
                {
                    $data["message"] = "Code already used";
                }
                else if($item->blocked == 1)
                {
                    $data["message"] = "Code is blocked";
                }
                else if($item->archived == 1)
                {
                    $data["message"] = "Code doesn't exists";
                }
                else if($item->customer_id != Self::$customer_id)
                {
                    $data["message"] = "Code not found.";
                }
                else
                {
                    $data["status"] = "success-prod-code";
                    Item_code::use_item_code_single($item, $slot);
                }
            }
            else
            {
                $data["message"] = "Code does not exists";
            }
        }
        else
        {
            $data["message"] = "Slot does not exists";
        }

        return json_encode($data);
    }


    public function transfer_item_code()
    {
        $item_code_id   = Request::input("item_code_id");
        $item           = Tbl_item_code::where("item_code_id",$item_code_id)->first();
        if($item)
        {
            if($item->used == 1)
            {
                $data["message"] = "Code already used";
            }
            else if($item->blocked == 1)
            {
                $data["message"] = "Code is blocked";
            }
            else if($item->archived == 1)
            {
                $data["message"] = "Code doesn't exists";
            }
            else if($item->customer_id != Self::$customer_id)
            {
                $data["message"] = "Code not found.";
            }
            else
            {
                $data["item"]  = $item;
                $data["_customer"] = $this->get_downline_customer();
            }
        }
        else
        {
            $data["message"] = "Code does not exists";
        }



        if(isset($data["message"]))
        {
            return json_encode($data["message"]);
        }
        else
        {
            return view('mlm.slots.transfer_item_code',$data);
        }
    }

    public function transfer_item_code_post()
    {
        $item_activation_code = Request::input("item_activation_code");
        $customer_id          = Request::input("customer_id");

        $customer             = $this->check_downline_customer($customer_id);
        $item                 = Tbl_item_code::where("item_activation_code",$item_activation_code)->first();
        
        if($customer)
        {
            if($item)
            {
                if($item->used == 1)
                {
                    $data["message"] = "Code already used";
                }
                else if($item->blocked == 1)
                {
                    $data["message"] = "Code is blocked";
                }
                else if($item->archived == 1)
                {
                    $data["message"] = "Code doesn't exists";
                }
                else if($item->customer_id != Self::$customer_id)
                {
                    $data["message"] = "Code not found.";
                }
                else
                {
                    $insert_log["item_code_transfer_by"]   = Self::$customer_id;
                    $insert_log["item_code_transfer_to"]   = $customer_id;
                    $insert_log["item_code_id"]            = $item->item_code_id; 
                    $insert_log["item_code_transfer_date"] = Carbon::now(); 
                    Tbl_item_code_transfer_log::insert($insert_log);

                    
                    $update["customer_id"] = $customer_id;
                    Tbl_item_code::where("item_activation_code",$item_activation_code)->update($update);
                    $data["status"] = "success-transfer-prod-code";
                }
            }
            else
            {
                $data["message"] = "Code does not exists";
            }
        }
        else
        {
            $data["message"] = "Customer does not exists";
        }

        return json_encode($data);
    }

    public function transfer_mem_code()
    {
        $mem_code_id    = Request::input("mem_code_id");
        $mem            = Tbl_membership_code::where("membership_code_id",$mem_code_id)->package()->membership()->first();
        if($mem)
        {
            if($mem->used == 1)
            {
                $data["message"] = "Code already used";
            }
            else if($mem->blocked == 1)
            {
                $data["message"] = "Code is blocked";
            }
            else if($mem->archived == 1)
            {
                $data["message"] = "Code doesn't exists";
            }
            else if($mem->customer_id != Self::$customer_id)
            {
                $data["message"] = "Code not found.";
            }
            else
            {
                $data["mem"]       = $mem;
                // $data["_customer"] = Tbl_customer::where('shop_id', Self::$shop_id)->where("customer_id","!=",Self::$customer_id)->get();
                
                $data["_customer"] = $this->get_downline_customer();
            }
        }
        else
        {
            $data["message"] = "Code does not exists";
        }


        if(isset($data["message"]))
        {
            return json_encode($data["message"]);
        }
        else
        {
            return view('mlm.slots.transfer_mem_code',$data);
        }
    }

    public function transfer_mem_code_post()
    {
        $membership_activation_code = Request::input("membership_activation_code");
        $customer_id                = Request::input("customer_id");

        $customer                   = $this->check_downline_customer($customer_id);
        $mem                        = Tbl_membership_code::where("membership_activation_code",$membership_activation_code)->first();
        
        if($customer)
        {
            if($mem)
            {
                if($mem->used == 1)
                {
                    $data["message"] = "Code already used";
                }
                else if($mem->blocked == 1)
                {
                    $data["message"] = "Code is blocked";
                }
                else if($mem->archived == 1)
                {
                    $data["message"] = "Code doesn't exists";
                }
                else if($mem->customer_id != Self::$customer_id)
                {
                    $data["message"] = "Code not found.";
                }
                else
                {
                    $insert_log["membership_code_transfer_by"]   = Self::$customer_id;
                    $insert_log["membership_code_transfer_to"]   = $customer_id;
                    $insert_log["membership_code_id"]            = $mem->membership_code_id; 
                    $insert_log["membership_code_transfer_date"] = Carbon::now(); 
                    Tbl_membership_code_transfer_log::insert($insert_log);

                    
                    $update["customer_id"] = $customer_id;
                    Tbl_membership_code::where("membership_activation_code",$membership_activation_code)->update($update);
                    $data["status"] = "success-transfer-mem-code";
                }
            }
            else
            {
                $data["message"] = "Code does not exists";
            }
        }
        else
        {
            $data["message"] = "Customer does not exists";
        }

        return json_encode($data);
    }

    public function get_downline_customer()
    {
        $_customer  = Tbl_tree_placement::leftJoin("tbl_mlm_slot as parent_table",'parent_table.slot_id','=','tbl_tree_placement.placement_tree_parent_id')
                                        ->leftJoin("tbl_mlm_slot as child_table",'child_table.slot_id','=','tbl_tree_placement.placement_tree_child_id')
                                        ->select("parent_table.slot_owner as parent_customer_id","child_table.slot_owner as child_customer_id","first_name","last_name","middle_name")
                                        ->leftJoin("tbl_customer","tbl_customer.customer_id","=","child_table.slot_owner")
                                        ->where("child_table.slot_owner","!=",Self::$customer_id)
                                        ->where("parent_table.shop_id","=",Self::$shop_id)
                                        ->groupBy("child_table.slot_owner")
                                        ->orderBy('tbl_customer.first_name')
                                        ->get();

        return $_customer;
    }

    public function check_downline_customer($customer_id)
    {
        $customer   = Tbl_tree_placement::leftJoin("tbl_mlm_slot as parent_table",'parent_table.slot_id','=','tbl_tree_placement.placement_tree_parent_id')
                                        ->leftJoin("tbl_mlm_slot as child_table",'child_table.slot_id','=','tbl_tree_placement.placement_tree_child_id')
                                        ->select("parent_table.slot_owner as parent_customer_id","child_table.slot_owner as child_customer_id","first_name","last_name","middle_name")
                                        ->leftJoin("tbl_customer","tbl_customer.customer_id","=","child_table.slot_owner")
                                        ->where("child_table.slot_owner","!=",Self::$customer_id)
                                        ->where("child_table.slot_owner","=",$customer_id)
                                        ->where("parent_table.shop_id","=",Self::$shop_id)
                                        ->groupBy("child_table.slot_owner")
                                        ->first();

        return $customer;                                      
    }
    public function use_product_code()
    {
        $data['action'] = '/mlm/slot/use_product_code/validate';
        $data['confirm_action'] = '/mlm/slot/use_product_code/to_slot';
        return view('mlm.slots.use_product_code',$data);
    }
    public function use_product_code_validate()
    {
        $mlm_pin = Request::input('mlm_pin');
        $mlm_activation = Request::input('mlm_activation');

        $shop_id = Self::$shop_id;

        $check = Item::check_product_code($shop_id, $mlm_pin, $mlm_activation);
        $return = [];
        if($check == true)
        {
            $return['status'] = 'success';
            $return['mlm_pin'] = $mlm_pin;
            $return['mlm_activation'] = $mlm_activation;
            $return['call_function'] = 'success_validation';
        }
        else
        {
            $return['status'] = 'error';
            $return['message'] = "Pin number and activation code doesn't exist.";
        }

        return json_encode($return);
    }
    public function to_slot()
    {
        $data['mlm_pin'] = Request::input('mlm_pin');
        $data['mlm_activation'] = Request::input('mlm_activation');
        $data["_slot"]    = Tbl_mlm_slot::where('slot_owner', Self::$customer_id)->membership()->get();
        $data['action'] = '/mlm/slot/use_product_code/confirmation';
        $data['confirm_action'] = '/mlm/slot/use_product_code/confirmation/submit';

        return view('mlm.slots.choose_slot',$data);
    }
    public function confirmation()
    {
        $data['mlm_pin'] = Request::input('mlm_pin');
        $data['mlm_activation'] = Request::input('mlm_activation');
        $data['slot_no'] = Request::input('slot_no');

        $data['status'] = 'success';
        $data['call_function'] = 'success_slot';

        return json_encode($data);
    }
    public function confirmation_submit()
    {
        $data['mlm_pin'] = Request::input('mlm_pin');
        $data['mlm_activation'] = Request::input('mlm_activation');
        $data['slot_no'] = Request::input('slot_no');
        
        $data['message'] = "&nbsp; &nbsp; Are you sure you wan't to use this PIN (<b>".$data['mlm_pin']."</b>) and Activation code (<b>".$data['mlm_activation']."</b>) in your Slot No <b>".$data['slot_no']."</b> ?";
        $data['action'] = '/mlm/slot/use_product_code/confirmation/used';

        return view('mlm.slots.confirm_product_code',$data);
    }
    public function use_submit()
    {
        $mlm_pin = Request::input('mlm_pin');
        $mlm_activation = Request::input('mlm_activation');
        $slot_no = Request::input('slot_no');

        $slot_id    = Tbl_mlm_slot::where('slot_no', $slot_no)->where('slot_owner', Self::$customer_id)->value('slot_id');

        $shop_id = Self::$shop_id;
        $consume['name'] = 'customer_product_code';
        $consume['id'] = Self::$customer_id;
        $val = Warehouse2::consume_product_codes($shop_id, $mlm_pin, $mlm_activation, $consume);

        if(is_numeric($val))
        {
            MLM2::purchase($shop_id, $slot_id, $val);
            $return['status'] = 'success';
            $return['call_function'] = 'success_used';
        }
        else
        {
            $return['status'] = 'error';
            $return['status'] = $val;
        }

        return json_encode($return);
    }

    public function message()
    {
        $data["message"] = Request::input("message");
        return view("member.message", $data);
    }
}