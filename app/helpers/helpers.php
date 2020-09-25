<?php
function floatvalser($amount)
{
    if(is_infinite($amount))
    {
        return 0;
    }
    else
    {
        return $amount;
    }
    
}
function hasWord($word, $txt) {
    $patt = "/(?:^|[^a-zA-Z])" . preg_quote($word, '/') . "(?:$|[^a-zA-Z])/i";
    return preg_match($patt, $txt);
}
function time_ago($datetime, $full = false) 
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'yr',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hr',
        'i' => 'min',
        's' => 'sec',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
function check_ifexist($string, $arr)
{
    if(in_array($string, $arr))
    {
        return 'checked';
    }
}
function gb_convert_time_from_db_to_timesheet($db_time)
{
    if($db_time == "00:00:00")
    {
        $return = "";
    }
    else
    {
        $return = $db_time;
    }

    return $return;
}
function array_to_object($array) {
         return (object) $array;
}

function ctopercent($flt)
{
    return number_format($flt * 100, 0) . "%";
}
function randomPassword()
{
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function c_time_to_int($time)
{
    $time = date("H:i", strtotime($time));
    $return = strtotime("01/01/70 " . $time . " UTC");
    return $return;
}
function space_to_plus($code)
{
    $code = str_replace(" ", "+", $code);
    return $code;
}
function code_to_word($code)
{
    $code = str_replace("_", " ", $code);
    $code = str_replace("first", "1st", $code);
    $code = ucwords($code);
    return $code;
}
function payroll_currency($amount)
{
    return "PHP " . number_format($amount, 2);
}
function payroll_date_format($date)
{
    return date("F d, Y", strtotime($date));
}
function convert_seconds_to_hours_minutes($format = "H:i", $d)
{
    date_default_timezone_set('UTC');
    $r = date($format, $d);
    date_default_timezone_set(config('app.timezone'));
    return $r;
}

function createPath($path)
{
    if (is_dir($path)) return true;
    $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
    $return = createPath($prev_path);
    return ($return && is_writable($prev_path)) ? mkdir($path) : false;
}
function underscore2Camelcase($str)
{
  // Split string in words.
  $words = explode('_', strtolower($str));

  $return = '';
  foreach ($words as $word) {
    $return .= " " . ucfirst(trim($word));
  }

  return $return;
}
function currency($symbol,$amount)
{
    return $symbol ." " .number_format($amount, 2);
}
function datepicker_input($date_str)
{
    return date("Y-m-d", strtotime($date_str));
}
function convertToNumber($str)
{
    return preg_replace("/([^0-9\\.])/i", "", $str);
}
function dateFormat($string)
{
    return date_format(date_create($string),"m/d/Y");
}
function deleteDir($dirPath) 
{
    if (! is_dir($dirPath)) {
        throw new InvalidArgumentException("$dirPath must be a directory");
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dirPath);
}
function hasSubdomain()
{
    if(!isset($_SERVER["HTTP_HOST"]))
    {
         $urlobj= "http://my168shop-primia.c9users.io";
    }
    else
    {
         $urlobj=$_SERVER["HTTP_HOST"];
    }

    $exploded = explode('.',$urlobj);
    return (count($exploded) > 2);
}
function get_domain()
{
    if(!isset($_SERVER["HTTP_HOST"]))
    {
         $urlobj= "http://my168shop-primia.c9users.io";
    }
    else
    {
         $urlobj=parse_url($_SERVER["HTTP_HOST"]);
    }
   
    
    if(isset($urlobj['host']))
    {
        $domain=$urlobj['host'];   
    }
    else
    {
        if(!isset($urlobj['path']))
        {
            $domain = "c9users.io";
        }
        else
        {
            $domain=$urlobj['path'];
        }
    }
    
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs))
    {
        return $regs['domain'];
    }
    
    return false;
}
function ordinal($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }
 function invoice_generator($id)
    {
        $count_of_zero = 5;
        $count_of_id_length =  preg_match_all( "/[0-9]/", $id );
        if($count_of_id_length >= $count_of_zero)
        {
            $count_of_zero = $count_of_zero + 5;
            if($count_of_id_length >= $count_of_zero)
            {
                $count_of_zero = $count_of_zero + 5; 
                if($count_of_id_length >= $count_of_zero)
                {
                  $count_of_zero = $count_of_zero + 5;  
                }
            }
        }
        $n2 = str_pad($id + 1, $count_of_zero, 0, STR_PAD_LEFT);
        return $n2;
    }
function mlm_plan_triger($marketing_plan_trigger = null)
{
    
    $html = "<select name='marketing_plan_trigger' class='form-control' readonly>";
    if($marketing_plan_trigger != null && $marketing_plan_trigger == "Slot Creation")
    {
        $html .= "<option value='Slot Creation' selected>Slot Creation</option>";
        $html .= "<option value='Product Repurchase'>Product Repurchase</option>";
    }
    elseif($marketing_plan_trigger != null && $marketing_plan_trigger == "Product Repurchase")
    {
        $html .= "<option value='Slot Creation'>Slot Creation</option>";
        $html .= "<option value='Product Repurchase' selected>Product Repurchase</option>";
    }
    else
    {
        $html .= "<option value='Slot Creation'>Slot Creation</option>";
        $html .= "<option value='Product Repurchase'>Product Repurchase</option>";
    }
    
    
    $html .= "</select>";
    return $html;
} 
function mlm_plan_release_schedule($marketing_plan_enable = null)
{
    $html = "<select name='marketing_plan_release_schedule' class='form-control marketing_plan_release_schedule' onChange='time_changer()'> ";
    if($marketing_plan_enable != null && $marketing_plan_enable == 1)
    {
        $html .= "<option value='1' selected>Instant</option>";   
        $html .= "<option value='2'>Daily</option>";
        $html .= "<option value='3'>Weekly</option>";
        $html .= "<option value='4'>Monthly</option>";
    }
    elseif ($marketing_plan_enable != null && $marketing_plan_enable == 2)
    {
        $html .= "<option value='1'>Instant</option>";
        $html .= "<option value='2' selected>Daily</option>";
        $html .= "<option value='3'>Weekly</option>";
        $html .= "<option value='4'>Monthly</option>";
    }
    elseif ($marketing_plan_enable != null && $marketing_plan_enable == 3)
    {
        $html .= "<option value='1'>Instant</option>";
        $html .= "<option value='2'>Daily</option>";
        $html .= "<option value='3' selected>Weekly</option>";
        $html .= "<option value='4'>Monthly</option>";
    }
    elseif ($marketing_plan_enable != null && $marketing_plan_enable == 4)
    {
        $html .= "<option value='1'>Instant</option>";
        $html .= "<option value='2'>Daily</option>";
        $html .= "<option value='3'>Weekly</option>";
        $html .= "<option value='4' selected>Monthly</option>";
    }
    else
    {
        $html .= "<option value='1'>Instant</option>";
        $html .= "<option value='2'>Daily</option>";
        $html .= "<option value='3'>Weekly</option>";
        $html .= "<option value='4'>Monthly</option>";
    }
    $html .= "</select>";
    return $html;
}   
function mlm_plan_release_schedule_hour($active = null)
{
    $data["_time"][1] = "01:00:00";
    $data["_time"][2] = "02:00:00";
    $data["_time"][3] = "03:00:00";
    $data["_time"][4] = "04:00:00";
    $data["_time"][5] = "05:00:00";
    $data["_time"][6] = "06:00:00";
    $data["_time"][7] = "07:00:00";
    $data["_time"][8] = "08:00:00";
    $data["_time"][9] = "09:00:00";
    $data["_time"][10] = "10:00:00";
    $data["_time"][11] = "11:00:00";
    $data["_time"][12] = "12:00:00";
    $data["_time"][13] = "13:00:00";
    $data["_time"][14] = "14:00:00";
    $data["_time"][15] = "15:00:00";
    $data["_time"][16] = "16:00:00";
    $data["_time"][17] = "17:00:00";
    $data["_time"][18] = "18:00:00";
    $data["_time"][19] = "19:00:00";
    $data["_time"][20] = "20:00:00";
    $data["_time"][21] = "21:00:00";
    $data["_time"][22] = "22:00:00";
    $data["_time"][23] = "23:00:00";
    $data["_time"][24] = "24:00:00"; 
    $html = "<select name='hours' class='form-control'>";
    foreach ($data["_time"] as $value) {
        $selected = "";
        if($active == $value)
        {
            $selected = "selected";
        }
        $html .= "<option value='".$value."' ".$selected.">".$value."</option>";
    }
    $html .= "</select>";
    return $html;
}
function mlm_plan_release_schedule_hour_12($active = null)
{
    $data["_time"][1] = "01:00:00";
    $data["_time"][2] = "02:00:00";
    $data["_time"][3] = "03:00:00";
    $data["_time"][4] = "04:00:00";
    $data["_time"][5] = "05:00:00";
    $data["_time"][6] = "06:00:00";
    $data["_time"][7] = "07:00:00";
    $data["_time"][8] = "08:00:00";
    $data["_time"][9] = "09:00:00";
    $data["_time"][10] = "10:00:00";
    $data["_time"][11] = "11:00:00";
    $data["_time"][12] = "12:00:00";
    $html = "<select name='hours' class='form-control'>";
    foreach ($data["_time"] as $value) {
        $selected = "";
        if($active == $value)
        {
            $selected = "selected";
        }
        $html .= "<option value='".$value."' ".$selected.">".$value."</option>";
    }
    $html .= "</select>";
    return $html;
}
function mlm_plan_release_schedule_day($active = null)
{
    $week_days = ["monday","tuesday","wednesday","friday","saturday","sunday"];
    $html = "<select name='week_days' class='form-control'>";
    foreach($week_days as $value)
    {
        $selected = "";
        if($active == $value)
        {
            $selected = "selected";
        }
        $html .= "<option value='".$value."' ".$selected.">".$value."</option>";
    }
    $html .= "</select>";
    return $html;
}
function mlm_plan_release_schedule_day_month($active = null)
{
    $month_days = ["1","2","3","4","5","6","7","8","8","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28"];
    $html = "<select name='month_day' class='form-control'>";
    foreach($month_days as $value)
    {
        $selected = "";
        if($active == $value)
        {
            $selected = "selected";
        }
        $html .= "<option value='".$value."' ".$selected.">".$value."</option>";
    }
    $html .= "</select>";
    return $html;
}
function mlm_slot_postion($position, $active = null)
{
    // slot_position
    $html = "";
    $html .= '<select class="form-control chosen-slot_position" name="slot_position">';
    if($active != null)
    {
        $html = '<input type="text" class="form-control input-v2" value="'.$active.'" disabled="disabled">';
        $html .= '<input type="hidden" class="form-control input-v2" name="slot_position" value="'.strtolower($active).'">';
        return $html;
    }

    if($active == 'left' || $active == 'Left')
    {
        $html .= '    <option value="left" selected>left</option>';
    }
    else
    {
        $html .= '    <option value="left">left</option>';
    }

    if($active == 'right' || $active == 'Right')
    {
        $html .= '    <option value="right" selected>right</option>';
    }
    else
    {
        $html .= '    <option value="right">right</option>';
    }
    
    $html .= '</select>';
    return $html;
}
function mlm_slot_postion_disabled($position)
{
    // slot_position
    $html = "";
    $html .= '<select class="form-control chosen-slot_position" name="slot_position" disabled="disabled">';
    $html .= '    <option value="left">left</option>';
    $html .= '    <option value="right">right</option>';
    $html .= '</select>';
    return $html;
}

function is_serialized($data) 
{
    // if it isn't a string, it isn't serialized
    if ( !is_string( $data ) )
        return false;
    $data = trim( $data );
    if ( 'N;' == $data )
        return true;
    if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
        return false;
    switch ( $badions[1] ) {
        case 'a' :
        case 'O' :
        case 's' :
            if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                return true;
            break;
        case 'b' :
        case 'i' :
        case 'd' :
            if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                return true;
            break;
    }
    return false;
}

function get_discount_price($price = null, $discount_type = null, $discount_value = null)
{

    if($price !== null)
    {
        if($discount_type !== null)
        {
            if($discount_value !== null)
            {
                $return_price = 0;

                // fixed
                if($discount_type === 0)
                {
                    $return_price = $price - $discount_value;
                }
                // percentage
                elseif($discount_type == 1)
                {
                    $return_discount = $price *  ($discount_value/100);
                    $return_price = $price - $return_discount;
                }
                else
                {
                   $return_price = $price - $discount_value; 
                }
                return $return_price;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 0;
        }
    }
    else
    {
        return 0;
    }
    
}

function name_format_from_customer_info($customer_info)
{
    if($customer_info != null)
    {
        if(isset($customer_info->title_name))
        {
            $name = strtoupper($customer_info->title_name) . ' ' . strtoupper($customer_info->first_name) . ' ' . ($customer_info->middle_name ? strtoupper(substr($customer_info->middle_name, 0, 1)) . '.' : '') . ' ' . strtoupper($customer_info->last_name) . ' ' . strtoupper($customer_info->suffix_name);
            return $name; 
        }
    }
}
function phone_number($customer_info)
{
    $ret = null;
    if($customer_info != null)
    {
        if(isset($customer_info->customer_mobile))
        {
            $p1 = null;
            $p2 = null;
            if($customer_info->customer_phone != null)
            {
                $p1 = $customer_info->customer_phone;
            }
            if($customer_info->customer_mobile != null)
            {
                $p2 = $customer_info->customer_mobile;
            }
            if(strlen($p1) == 10)
            {
                $p1 = '(+63)' . $p1;
            }
            else if(strlen($p1) == 11)
            {
                $p1 = substr($p1, 1);
                $p1 = '(+63)' . $p1;
            }

            if(strlen($p2) == 10)
            {
                $p2 = '(+63)' . $p2;
            }
            else if(strlen($p2) == 11)
            {
                $p2 = substr($p2, 1);
                $p2 = '(+63)' . $p2;
            }


            if($p1 != null && $p1 != '--' )
            {
                $ret = $p1;
                if($p2 != null)
                {
                    $ret = $p1.'/'.$p2;
                }
                else
                {
                    $ret = $p2;
                }
            }
            else
            {
                if($p2 != null)
                {
                    $ret = $p2;
                }
            }
        }
    }
    return $ret;
}
function address_customer_info($customer_info)
{
    // street, city, province, zipcode.
    $adderss = '';
    if(isset($customer_info->customer_street))
    {
        // $adderss = $customer_info->customer_street  . ', ' . $customer_info->customer_state . ', ' .  $customer_info->customer_zipcode;
        if($customer_info->customer_street != null)
        {
            $adderss = $customer_info->customer_street;
        } 
        if($customer_info->customer_city != null)
        {
            $adderss = $adderss . ', ' . $customer_info->customer_city;
        }    
        if($customer_info->customer_state != null)
        {
            $adderss = $adderss . ', ' . $customer_info->customer_state;
        }
        if($customer_info->customer_zipcode != null)
        {
            $adderss = $adderss . ', ' . $customer_info->customer_zipcode;
        }
    }
    return $adderss;
}
function limit_foreach($iterable, $limit) 
{
    foreach ($iterable as $key => $value) 
    {
        if (!$limit--) break;
        yield $key => $value;
    }
}
function payout_getway()
{
    $data[0] = 'Bank Deposit';
    $data[1] = 'Cheque';
    return $data;
}

function divide($num1, $num2)
{
    $result = 0;
    if($num1 > 0 && $num2 > 0)
    {
       $result =  $num1 / $num2;
    }

    return $result;
}

function limit_char($x, $length)
{
    if(strlen($x)<=$length)
    {
        echo $x;
    }
    else
    {
        $y=substr($x,0,$length) . '...';
        echo $y;
    }
}

/* return null value to zero */
function n2z($value)
{
    if(is_null($value))
    {
        $value = 0;
    }
    return $value;
}

function debit_credit($type, $amount)
{
    if($type == 'Debit' || $type == 'debit')
    {
        return 1 * $amount;
    }
    else
    {
        return -1 * $amount;
    }
}
function mlm_profile($customer)
{
    if($customer)
    {
        $customer->profile != null ? $profile = $customer->profile :  $profile = '/assets/mlm/default-pic.png';
        return '<img src="'.$profile.'" class="img-responsive" >';
        // style="height: 200px; width: 100%; object-fit: contain;"
    }
}
function mlm_profile_link($customer)
{
    if($customer)
    {
        $customer->profile != null ? $profile = $customer->profile :  $profile = '/assets/mlm/default-pic.png';
        return $profile;
    }
}
function get_ip_address()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}
function shuffle_assoc($list) 
{ 
    $shuffleKeys = array_keys($list->toArray());
    shuffle($shuffleKeys);
    $newArray = array();
    foreach($shuffleKeys as $key) 
    {
        $newArray[$key] = $list[$key];
    }
    return collect($newArray);
} 
function get_payment_method_mlm($id)
{
    $data[1] = 'Cash';
    $data[2] = 'GC';
    $data[3] = 'E-Wallet';
    $data[4] = 'V-Money';

    return $data[$id];
}
function isJson($string) 
{
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}
function get_request_old($data, $name, $array_name = null)
{
    if (Request::old($name)) 
    {
        return $name;
    }
    elseif (isset($data->$name))
    {
        return $data->$name;
    }
    elseif ($array_name)
    {
        if (isset($data->$array_name)) 
        {
            return $data->$array_name;
        }
        else
        {
            return '';
        }
    }
    else
    {
        return '';
    }
}