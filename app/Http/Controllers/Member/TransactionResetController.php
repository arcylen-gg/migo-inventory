<?php
namespace App\Http\Controllers\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Globals\AccountingTransaction;

use Carbon\Carbon;
use Session;
use PDF;
use Crypt;

class TransactionResetController extends Member
{
	public function getIndex()
	{
		$data['page'] = "Reset Transaction";
		$data['action'] = "/member/transaction/reset/submit-reset";
		$data['encrypt_pass'] = Crypt::encrypt("water123");

		return view("member.accounting_transaction.reset.transaction_reset", $data);
	}
	public function postSubmitReset(Request $request)
	{
		$json = null;
		$decrypted = Crypt::decrypt($request->encrypt_pass);
		if("RESET" === $request->entry_pass)
		{
			$return = AccountingTransaction::reset_transaction($this->user_info->shop_id, $request->reset_transaction);			
			if(!$return)
			{
				$json['status'] = 'success';
				$json['status_message'] = 'Successful Reset';
				$json['call_function'] = 'success_reset';
			}
		}
		else
		{
			$json['status'] = 'error';
			$json['status_message'] = 'Password is incorrect';
		}
		return json_encode($json);
	}
}