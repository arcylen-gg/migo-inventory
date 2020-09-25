<?php

namespace App\Http\Controllers;

/*use Illuminate\Http\Request;*/

use Request;
use Redirect;
use App\Http\Controllers\Controller;
use App\Globals\Dragonpay2\Dragon_RequestPayment;
use App\Globals\Mlm_member;
use App\Models\Tbl_mlm_slot;
use App\Globals\Mlm_plan;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_gc;
use Session;
use Carbon\Carbon;
use App\Globals\Ec_order;
use DB;
class PaymentController extends Controller
{
    protected $_merchantid = 'MYPHONE' ;
    protected $_merchantkey = 'Ez9MiNqWBS2BHuO' ;
    
    public function index()
    {
        $data['_page'] = "Dragonpay";
        return view('payment', $data);
    }

    public function onSubmitPayment()
    {
        $request = Request::all();
        $requestpayment = new Dragon_RequestPayment($this->_merchantkey);

        $this->_data = array(
            'merchantid'    => $requestpayment->setMerchantId($this->_merchantid),
            'txnid'         => $requestpayment->setTxnId($request['txnid']),
            'amount'        => $requestpayment->setAmount($request['amount']),
            'ccy'           => $requestpayment->setCcy($request['ccy']),
            'description'   => $requestpayment->setDescription($request['description']),
            'email'         => $requestpayment->setEmail($request['email']),
            'digest'        => $requestpayment->getdigest(),
            'param1'        => "test"
        );

        Dragon_RequestPayment::make($this->_merchantkey, $this->_data);   
    }
}
