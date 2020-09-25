<?php
namespace App\Globals\PayMaya\Checkout;

use App\Globals\PayMaya\Model\Checkout\Buyer;
use App\Globals\PayMaya\Model\Checkout\Address;
use App\Globals\PayMaya\Model\Checkout\Contact;

class User
{
	private $firstName;
	private $middleName;
	private $lastName;
	private $contact;
	private $shippingAddress;
	private $billingAddress;

	public function __construct()
	{
		$this->firstName = "---";
		$this->middleName = "---";
		$this->lastName = "---";

		// Contact
		$this->contact = new Contact();
		$this->contact->phone = "";
		$this->contact->email = "";

		// Address
		$address = new Address();
		$address->line1 = "---";
		$address->line2 = "";
		$address->city = "---";
		$address->state = "---";
		$address->zipCode = "";
		$address->countryCode = "PH";
		$this->shippingAddress = $address;
		$this->billingAddress = $address;
	}
	
	public function buyerInfo()
	{
		$buyer = new Buyer();
		$buyer->firstName = $this->firstName;
		$buyer->middleName = $this->middleName;
		$buyer->lastName = $this->lastName;
		$buyer->contact = $this->contact;
		$buyer->shippingAddress = $this->shippingAddress;
		$buyer->billingAddress = $this->billingAddress;
		return $buyer;
	}
}
