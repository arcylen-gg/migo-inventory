<?php
namespace App\Globals;


use Schema;
use Session;
use DB;
use Carbon\Carbon;
use Request;

use App\Globals\Mlm_compute;
use App\Globals\Mlm_slot_log;
use App\Globals\Mlm_complan_manager;


use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_customer;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_user;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_binary_setttings;
use App\Models\Tbl_membership;
use App\Models\Tbl_customer_search;
use App\Models\Tbl_customer_other_info;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_mlm_matching_log;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_item_code_item;
use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership_code_invoice;
use App\Models\Tbl_voucher_item;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_email_content;
class Mlm_seed
{   
    public static function seed_mlm($shop_id)
    {

        $insert[1]['email_content_key'] = 'success_register';
        $insert[1]['email_content'] = '<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Greetings!</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">You have successfully completed yourPhilTECH registration made under the name [name_of_registrant] with TIN [tin_of_registrant] . Your PhilTECH Username is [user_name] .</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">To start accessing our E-commerce website, log-in with your PhilTECH Username and Password. Please click on the link provided below. If clicking the link does not seem to work, you can copy and paste the link into your browser&rsquo;s address window.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">philtechglobalinc.com/mlm</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">For inquiries, please feel free to contact us at (0917) 542-2614 (Globe Mobile), (062) 310-2256 (Globe Landline), <a href="mailto:philtechglobalmainoffice@gmail.com">philtechglobalmainoffice@gmail.com</a> (Email), or you may visit our Main Office located at PhilTECH Bldg. (2<sup>nd</sup> Level), Gallera Road, Guiwan, Zamboanga City.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Best Regards,</p><p>&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;"><strong>The PhilTECH Admin Team</strong></p>';
        $insert[1]['email_content_subject'] = 'Success Registration'; 

        $insert[2]['email_content_key'] = 'membership_code_purchase';
        $insert[2]['email_content'] = '<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Greetings!</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">You have successfully purchased [membership_count] Membership Package. Your Membership Code is/are:</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">[membership_code]</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">As a PhilTECH VIP, you are entitled to Lifetime Privileges and Benefits exclusively designed for our VIPs. Experience shopping convenience like never before with our E-Commerce System. Enjoy Discounts and Earn Cashback + Rewards Points with every purchase on all products of the company. Just present your PhilTECH VIP Card during payment and all these exciting benefits will be yours.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">For inquiries, please feel free to contact us at (0917) 542-2614 (Globe Mobile), (062) 310-2256 (Globe Landline), <a href="mailto:philtechglobalmainoffice@gmail.com">philtechglobalmainoffice@gmail.com</a> (Email), or you may visit us at our Main Office located at PhilTECH Bldg. (2<sup>nd</sup> Level), Gallera Road, Guiwan, Zamboanga City.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Congratulations!</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Best Regards,</p><p>&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;"><strong>The PhilTECH Admin Team</strong></p>';
        $insert[2]['email_content_subject'] = 'Membership Code Purchase'; 

        $insert[3]['email_content_key'] = 'discount_card_purchase';
        $insert[3]['email_content'] = '<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Greetings!</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">You have successfully purchased a PhilTECH [membership_name]&nbsp;from [sponsor] Please read important details below:</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Date Issued:&nbsp;[date_issued]</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Date of Expiry: [date_expiry]</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">You may now enjoy a full year of Discount Privilege with every purchase on all products of the company by simply presenting your Discount Card during payment.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">For inquiries, please feel free to contact us at (0917) 542-2614 (Globe Mobile), (062) 310-2256 (Globe Landline), <a href="mailto:philtechglobalmainoffice@gmail.com">philtechglobalmainoffice@gmail.com</a> (Email), or you may visit us at our Main Office located at PhilTECH Bldg. (2<sup>nd</sup> Level), Gallera Road, Guiwan, Zamboanga City.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Congratulations!</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Sincerely,</p><p>&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;"><strong>The PhilTECH Admin Team</strong></p>';
        $insert[3]['email_content_subject'] = 'Discount Card Purchase'; 

        $insert[4]['email_content_key'] = 'e_wallet_transfer';
        $insert[4]['email_content'] = '<p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>Greetings!</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>&nbsp;</strong></p><div style="mso-element: para-border-div; border: none; border-bottom: solid windowtext 1.0pt; mso-border-bottom-alt: solid windowtext .75pt; padding: 0in 0in 1.0pt 0in;"><p class="MsoNormal" style="margin-bottom: .0001pt; border: none; mso-border-bottom-alt: solid windowtext .75pt; padding: 0in; mso-padding-alt: 0in 0in 1.0pt 0in;"><strong>Thank you for using E-wallet money transfer. We have successfully processed your transfer request.&nbsp; Transaction details are as follows;</strong></p></div><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>&nbsp;</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>Transaction #: [transaction_id]</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>Transaction Date: [transaction_date]</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>Amount transferred: [amount]</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>E-wallet Recipient: [sponsor]</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>E-wallet Transfer Fee: [fee]</strong></p><div style="mso-element: para-border-div; border: none; border-bottom: solid windowtext 1.0pt; mso-border-bottom-alt: solid windowtext .75pt; padding: 0in 0in 1.0pt 0in;"><p class="MsoNormal" style="margin-bottom: .0001pt; border: none; mso-border-bottom-alt: solid windowtext .75pt; padding: 0in; mso-padding-alt: 0in 0in 1.0pt 0in;"><strong>Total Amount charged on E-wallet: [amount_plus_fee]</strong></p></div><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>&nbsp;</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>If there is a problem with executing your request, please call us at 0917-542-2614 (Globe Mobile) or at (062) 310-2256 (Globe Landline). You can always check your transfer status on the Account Dashboard. Just click the link below:</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>(link to account dashboard)</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>&nbsp;</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>Best Regards,</strong></p><p>&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>PhilTech Admin Team</strong></p>';
        $insert[4]['email_content_subject'] = 'E-Wallet Transfer'; 

        $insert[5]['email_content_key'] = 'merchant_registration';
        $insert[5]['email_content'] = '<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Greetings!</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">You have successfully registered as a PhilTECH Merchant under the company name [business_name]&nbsp;with Mr./Ms. [name_of_merchant]&nbsp; as your representative. Your PhilTECH Username is [username].</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">To start registering your products and services through our Merchant Module System, log-in with your PhilTECH Username and Password. Please click on the link provided below. If clicking the link does not seem to work, you can copy and paste the link into your browser&rsquo;s address window.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">(Link to PhilTECH&rsquo;s Merchant Module System log-in page)</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">For inquiries, please feel free to contact us at (0917) 542-2614 (Globe Mobile), (062) 310-2256 (Globe Landline), <a href="mailto:philtechglobalmainoffice@gmail.com">philtechglobalmainoffice@gmail.com</a> (Email), or you may visit our Main Office located at PhilTECH Bldg. (2<sup>nd</sup> Level), Gallera Road, Guiwan, Zamboanga City.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Best Regards,</p><p>&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;"><strong>The PhilTECH Admin Team</strong></p>';
        $insert[5]['email_content_subject'] = 'Merchant Registration'; 

        $insert[6]['email_content_key'] = 'merchant_product_registration';
        $insert[6]['email_content'] = '<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Greetings!</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">You have successfully registered your product through the Merchant Module System. Please be advised that processing time is 1-3 working days before your products/services get uploaded to the PhilTECH website. This is to ensure that the company&rsquo;s set standards are being followed at all times.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">These are the list of all the registered product:</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">[products]&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">For inquiries, please feel free to contact us at (0917) 542-2614 (Globe Mobile), (062) 310-2256 (Globe Landline), <a href="mailto:philtechglobalmainoffice@gmail.com">philtechglobalmainoffice@gmail.com</a> (Email), or you may visit our Main Office located at PhilTECH Bldg. (2<sup>nd</sup> Level), Gallera Road, Guiwan, Zamboanga City.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Best Regards,</p><p>&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;"><strong>The PhilTECH Admin Team</strong></p>';
        $insert[6]['email_content_subject'] = 'Merchant Product Registration'; 

        $insert[7]['email_content_key'] = 'e_wallet_refill';
        $insert[7]['email_content'] = '<p class="MsoNormal" style="margin-bottom: .0001pt;">Greetings!</p><p class="MsoNormal" style="margin-bottom: .0001pt;">&nbsp;</p><div style="mso-element: para-border-div; border: none; border-bottom: solid windowtext 1.0pt; mso-border-bottom-alt: solid windowtext .75pt; padding: 0in 0in 1.0pt 0in;"><p class="MsoNormal" style="margin-bottom: .0001pt; border: none; mso-border-bottom-alt: solid windowtext .75pt; padding: 0in; mso-padding-alt: 0in 0in 1.0pt 0in;">We are pleased to inform you that we have already received your payment and had successfully processed your E-wallet funds replenishment request. Transaction details are as follows;</p><p class="MsoNormal" style="margin-bottom: .0001pt; border: none; mso-border-bottom-alt: solid windowtext .75pt; padding: 0in; mso-padding-alt: 0in 0in 1.0pt 0in;">&nbsp;</p></div><p class="MsoNormal" style="margin-bottom: .0001pt;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>Transaction #: [transaction_number]</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>Transaction Date: [transaction_date]</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>Amount transferred: [amount]</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>E-wallet Recipient: [sponsor]</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;"><strong>E-wallet Transfer Fee: [fee]</strong></p><p class="MsoNormal" style="margin-bottom: .0001pt;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt;">You may now enjoy using your E-wallet funds for any of your purchases. To check your new E-wallet balance, simply click the link provided below;</p><p class="MsoNormal" style="margin-bottom: .0001pt;">(link to e-wallet page)</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">For inquiries, please feel free to contact us at (0917) 542-2614 (Globe Mobile), (062) 310-2256 (Globe Landline), <a href="mailto:philtechglobalmainoffice@gmail.com">philtechglobalmainoffice@gmail.com</a> (Email), or you may visit our Main Office located at PhilTECH Bldg. (2<sup>nd</sup> Level), Gallera Road, Guiwan, Zamboanga City.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Best Regards,</p><p>&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;"><strong>The PhilTECH Admin Team</strong></p>';
        $insert[7]['email_content_subject'] = 'E-Wallet Refill'; 

        $insert[7]['email_content_key'] = 'inquire_current_points';
        $insert[7]['email_content'] = '<p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Greetings!</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Your current available points are [current_points] . Please be reminded that points may only be redeemed thru items and cannot be converted to cash.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">For inquiries, please feel free to contact us at (0917) 542-2614 (Globe Mobile), (062) 310-2256 (Globe Landline), <a href="mailto:philtechglobalmainoffice@gmail.com">philtechglobalmainoffice@gmail.com</a> (Email), or you may visit our Main Office located at PhilTECH Bldg. (2<sup>nd</sup> Level), Gallera Road, Guiwan, Zamboanga City.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Best Regards,</p><p>&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;"><strong>The PhilTECH Admin Team</strong></p>';
        $insert[7]['email_content_subject'] = 'Inquire Current Points'; 

        $insert[8]['email_content_key'] = 'redeem_points';
        $insert[8]['email_content'] = '<p>&nbsp;<span style="text-align: justify;">Greetings!</span></p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">You have successfully redeemed <u>[points]</u>. You may claim your <u>[item_redeem]</u>&nbsp;at any BCO branch Nationwide. Please present your VIP Card and any valid ID when claiming.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">For inquiries, please feel free to contact us at (0917) 542-2614 (Globe Mobile), (062) 310-2256 (Globe Landline), <a href="mailto:philtechglobalmainoffice@gmail.com">philtechglobalmainoffice@gmail.com</a> (Email), or you may visit our Main Office located at PhilTECH Bldg. (2<sup>nd</sup> Level), Gallera Road, Guiwan, Zamboanga City.</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">&nbsp;</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;">Best Regards,</p><p class="MsoNormal" style="margin-bottom: .0001pt; text-align: justify;"><strong>The PhilTECH Admin Team</strong></p>';
        $insert[8]['email_content_subject'] = 'Redeem Points'; 

        $insert[9]['email_content_key'] = 'front_forgot_password';
        $insert[9]['email_content'] = '<p>Hi [name],</p>
                                        <p>&nbsp;</p>
                                        <p>This email was sent automatically by [domain_name] in response to recover your password. This is done for your protection.</p>
                                        <p>&nbsp;</p>
                                        <p><h3>Here is your new password : <strong>[password]</strong></h3></p>
                                        <p>&nbsp;</p>
                                        <p>If you did not forget your password, please ignore this email.</p>
                                        <p>&nbsp;</p>
                                        <p>Thanks,</p>
                                        <p>Admin</p>
                                        <p>&nbsp;</p>';
        $insert[9]['email_content_subject'] = 'Forgot Password'; 

        $count = Tbl_email_content::where('shop_id', $shop_id)->where('archived',0)->count();
        $count_mail_c = count($insert);
        // if($count_mail_c > $count)
        // {
            foreach ($insert as $key => $value) 
            {
               $count = Tbl_email_content::where('shop_id', $shop_id)
               ->where('email_content_key', $value['email_content_key'])
               ->count();
               if($count == 0)
               {
                    $insert_mail['email_content_key'] = $value['email_content_key'];
                    $insert_mail['email_content'] = $value['email_content'];
                    $insert_mail['shop_id'] = $shop_id;
                    $insert_mail['date_created'] = Carbon::now();
                    $insert_mail['date_updated'] = Carbon::now();
                    $insert_mail['archived'] = 0;
                    $insert_mail['email_content_subject'] = $value['email_content_subject'];
                    Tbl_email_content::insert($insert_mail);
               }
            }
        // }
    }
}