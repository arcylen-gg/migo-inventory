<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


/**
 * Trackings Module - all tracking and shipment realated module
 *
 * @author Bryan Kier Aradanas
 * Route Controller
 */

class TrackingsController extends Member
{
    public function getIndex()
    {
        return view('member.trackings.trackings');
    }

    public function getCreateShipment()
    {
        $tracking["tracking"]["slug"]               = "usps";       
        $tracking["tracking"]["tracking_number"]    = "ABC123498";    //required
        $tracking["tracking"]["title"]              = "Title2";
        $tracking["tracking"]["smses"][0]           = "0986768767";
        $tracking["tracking"]["smses"][1]           = "0986768767";
        $tracking["tracking"]["emails"][1]          = "abc@gmail.com";
        $tracking["tracking"]["emails"][2]          = "bgd@gmail.com";
        $tracking["tracking"]["order_id"]           = "ID-23987";
        $tracking["tracking"]["order_id_path"]      = "http://digimahouse.dev/member/ecommerce/product_order/create_order?id=1";

        $tracking["tracking"]["custom_fileds"]["product_name"]  = "Product 1";
        $tracking["tracking"]["custom_fileds"]["product_price"] = "PHP2,309.00";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.aftership.com/v4/trackings",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($tracking),
            CURLOPT_HTTPHEADER => array(
                "aftership-api-key: 118485a6-ed28-4200-a924-ee42e5019b47",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        dd(json_decode($response));
    }

     public function getTrackingList()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.aftership.com/v4/trackings",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => [],
            CURLOPT_HTTPHEADER => array(
                "aftership-api-key: 118485a6-ed28-4200-a924-ee42e5019b47",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        dd(json_decode($response));
    }

}
