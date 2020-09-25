<?php 

namespace App\Globals\Dragonpay2;

use App\Globals\Dragonpay2\RequestForm;

class Dragon_RequestPayment
{
    public static $paymentUrl = 'http://test.dragonpay.ph/Pay.aspx';

	private $merchantkey;

	public function __construct($merchantkey)
    {
    	if (get_domain() == "c9users.io") 
    	{
    		self::$paymentUrl = "http://test.dragonpay.ph/Pay.aspx";
    	}
    	else 
    	{
    		self::$paymentUrl = "https://gw.dragonpay.ph/Pay.aspx";
    	}
    	
    	$this->merchantkey = $merchantkey;
    }

	private $merchantid;
	public function getMerchantId()
	{
		return $this->merchantid;
	}
	public function setMerchantId($val)
	{
		$this->digest = null; //need new signature if this is changed
		return $this->merchantid = $val;
	}

	private $txnid;
	public function getTxnId()
	{
		return $this->txnid;
	}
	public function setTxnId($val)
	{
		$this->digest = null; //need new signature if this is changed
		return $this->txnid = $val;
	}

	private $amount;
	public function getAmount()
	{
		$this->digest = null; //need new signature if this is changed
		return $this->amount;
	}

	public function setAmount($val)
	{		
		return $this->amount = number_format($val, 2, '.', '');
	}

	private $ccy;
	public function getCcy()
	{
		return $this->ccy;
	}

	public function setCcy($val)
	{
		return $this->ccy = $val;
	}

	private $description;
	public function getDescription()
	{
		return $this->description;
	}
	public function setDescription($val)
	{
		$this->digest = null; //need new signature if this is changed
		return $this->description = $val;
	}

	private $email;
	public function getEmail()
	{
		return $this->email;
	}
	public function setEmail($val)
	{
		$this->digest = null; //need new signature if this is changed
		return $this->email = $val;
	}

	private $digest;
	public function getdigest($refresh = false)
	{

		if((!$this->digest) || $refresh)
		{

			$param = array(
				'merchantid' 	=> $this->getMerchantId(), 
				'txnid' 		=> $this->getTxnId(),
				'amount' 		=> $this->getAmount(), 
				'ccy' 			=> $this->getCcy(), 
				'description' 	=> $this->getDescription(), 
				'email' 		=> $this->getEmail(), 
				'merchantkey' 	=> $this->merchantkey,  
			);

			$digest_string = implode(':', $param);
			
			$this->digest = sha1($digest_string);
		}
		return $this->digest;
	}

	protected static $fillable_fields = [
		'merchantid','txnid', 'amount',
		'ccy','description','email',
		'digest'
	];

	/**
	* IPay88 Payment Request factory function
	*
	* @access public
	* @param string $merchantid The merchant key provided by ipay88
	* @param hash $fieldValues Set of field value that is to be set as the properties
	*  Override `$fillable_fields` to determine what value can be set during this factory method
	* @example
	*  $request = IPay88\Payment\Request::make($merchantid, $fieldValues)
	* 
	*/
	public static function make($merchantid, $fieldValues)
	{
		$request = new Dragon_RequestPayment($merchantid);
		RequestForm::render($fieldValues, self::$paymentUrl);
	}

    /**
    * @access public
    * @param boolean $multiccy Set to true to get payments optinos for multi ccy gateway
    */
    public static function getPaymentOptions($multiccy = true)
    {
        $myrOnly = array(
        	2 => array('Credit Card','MYR'),
        	6 => array('Maybank2U','MYR'),
        	8 => array('Alliance Online','MYR'),
        	10=> array('AmOnline','MYR'),
        	14=> array('RHB Online','MYR'),
        	15=> array('Hong Leong Online','MYR'),
        	16=> array('FPX','MYR'),
        	20=> array('CIMB Click', 'MYR'),
        	22=> array('Web Cash','MYR'),
        	48=> array('PayPal','MYR'),
        	100 => array('Celcom AirCash','MYR'),
        	102 => array('Bank Rakyat Internet Banking','MYR'),
        	103 => array('AffinOnline','MYR')
        );

        $nonMyr = array(
        	25=> array('Credit Card','USD'),
        	35=> array('Credit Card','GBP'),
        	36=> array('Credit Card','THB'),
        	37=> array('Credit Card','CAD'),
        	38=> array('Credit Card','SGD'),
        	39=> array('Credit Card','AUD'),
        	40=> array('Credit Card','MYR'),
        	41=> array('Credit Card','EUR'),
        	42=> array('Credit Card','HKD'),
        );

        return $multiccy ? $nonMyr : $myrOnly;
    }

    
}
