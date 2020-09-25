<?php
namespace App\Http\Controllers\Mlm;
use App\Http\Controllers\Controller;
use Crypt;
use Redirect;
use Request;
use View;

class MlmChequeController extends Mlm
{
    public function index()
    {
    	return Self::show_maintenance();
        $data["page"] = "Cheque";
        return view("mlm.cheque", $data);
    }
}