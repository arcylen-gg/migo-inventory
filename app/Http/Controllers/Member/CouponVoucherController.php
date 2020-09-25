<?php
namespace App\Http\Controllers\Member;

use App\Globals\Ec_order;
use App\Globals\Invoice;
use App\Globals\Accounting;
use App\Globals\Ecom_Product;
use App\Globals\Pdf_global;
use App\Globals\Cart;

use App\Models\Tbl_customer;
use App\Models\Tbl_user;
use App\Models\Tbl_warehousea;
use App\Models\Tbl_category;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_coupon_code;
use App\Models\Tbl_coupon_code_product;
use App\Models\Tbl_payment_method;

use Request;
use Carbon\Carbon;
use Session;
use Redirect;
use PDF;

/**
 * Coupon Code Module - all coupon and voucher realated module
 *
 * @author Bryan Kier Aradanas
 * Route Controller
 */

class CouponVoucherController extends Member
{
    public function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    public function getIndex()
    {

    }

    public function getList()
    {
        $unused_coupon  = Tbl_coupon_code::where("used", 0)->where("tbl_coupon_code.shop_id", $this->getShopId()); 
        $used_coupon    = Tbl_coupon_code::where("used", 1)->order()->where("tbl_coupon_code.shop_id", $this->getShopId());

        /* Filter Coupon By Search */
        $search = Request::input('search');
        if($search)
        {
            $unused_coupon   = $unused_coupon->where("coupon_code","like","%$search%");
            $used_coupon     = $used_coupon->where("coupon_code","like","%$search%");
        }
        
        $data["unused_coupon"]  = $unused_coupon->paginate(10);
        $data["used_coupon"]    = $used_coupon->paginate(10);

        return view('member.ecommerce_coupon.coupon', $data);
    }

    public function getGenerateCode()
    {
        $data['_product'] = Ecom_Product::getProductList($this->getShopId(),0,1);
        return view('member.ecommerce_coupon.generate_coupon', $data);
    }

    public function getEditGenerateCode($coupon_id)
    {
        $data["coupon"]     = Tbl_coupon_code::where("coupon_code_id", $coupon_id)->first();
        $data["_coupon_product"]     = Tbl_coupon_code_product::where("coupon_code_id", $coupon_id)->get();
        $data['_product']   = Ecom_Product::getProductList($this->getShopId(),0,1);
        return view('member.ecommerce_coupon.generate_coupon', $data);
    }

    public function postGenerateCode()
    {
        $coupon_code_id             = Request::input('coupon_id');
        $coupon_product_id          = Request::input('coupon_product_id');
        $coupon_amount              = Request::input('coupon_amount');
        $coupon_type                = Request::input('coupon_amount_type');
        $coupon_minimum_quantity    = Request::input('coupon_minimum_quantity');
        $generate_count             = Request::input('generate_count') > 0 ? Request::input('generate_count') : 1;
        $all_product_id             = Request::input('all_product_id');

        while($generate_count != 0)
        {
            if(!$coupon_code_id)    
            {
                $coupon =  Cart::generate_coupon_code(8, $coupon_amount, $coupon_minimum_quantity, $coupon_type, $coupon_product_id, $all_product_id);
            }
            else
            {
                $coupon = Cart::update_coupon_code($coupon_code_id, $coupon_amount,$coupon_product_id, $coupon_minimum_quantity, $coupon_type, $all_product_id);
            }
            $generate_count--;
        }
        return json_encode($coupon);
    }

    //Ewen
    public function submit_coupon()
    {
        $return["message"] = "";
        $code              = Request::input("sent_code");
        $invoice_id        = Request::input("invoice_id");

        $coupon            = Tbl_coupon_code::where("coupon_code",$code)->where("shop_id",$this->user_info->shop_id)->first();
        if($coupon) 
        {
            if($coupon->used == 1)
            {
                if($invoice_id)
                {
                    $check_invoice = Tbl_ec_order::where('ec_order_id',$invoice_id)->first();
                    if($check_invoice)
                    {
                        $check_coupon = Tbl_coupon_code::where("coupon_code_id",$check_invoice->coupon_id)->first();
                        if($check_coupon)
                        {
                            if($check_coupon->coupon_code == $code)
                            {
                                $return["amount"]  = $coupon->coupon_code_amount;

                                if($coupon->coupon_discounted == "fixed")
                                {
                                    $return["type"]  = "fixed";
                                }
                                else if($coupon->coupon_discounted == "percentage")
                                {
                                    $return["type"]  = "percent";
                                }

                                $return["message"] = "Success";
                            }
                            else
                            {
                                $return["message"] = "Coupon already used"; 
                            }
                        }
                        else
                        {
                            $return["message"] = "Coupon already used";        
                        }
                    }
                    else
                    {
                        $return["message"] = "Coupon already used"; 
                    }
                }
                else
                {
                    $return["message"] = "Coupon already used"; 
                }
            }
            else if($coupon->blocked == 1)
            {
                $return["message"] = "Coupon is blocked";
            }
            else
            {
                $return["amount"]  = $coupon->coupon_code_amount;

                if($coupon->coupon_discounted == "fixed")
                {
                    $return["type"]  = "fixed";
                }
                else if($coupon->coupon_discounted == "percentage")
                {
                    $return["type"]  = "percent";
                }

                $return["message"] = "Success";
            }
        }
        else
        {
            $return["message"] = "Coupon does not exist";
        }




        return json_encode($return);
    }
}