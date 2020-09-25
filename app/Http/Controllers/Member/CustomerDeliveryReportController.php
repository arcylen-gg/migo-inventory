<?php
namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Globals\Cart2;
use App\Globals\WarehouseTransfer;
use App\Globals\Warehouse2;
use App\Globals\Item;
use App\Globals\AccountingTransaction;
use App\Globals\Transaction;
use App\Models\Tbl_warehouse_issuance_report;
use App\Globals\UnitMeasurement;

use Session;
use Carbon\Carbon;
use App\Globals\Pdf_global;

class CustomerDeliveryReportController extends Member
{
	public function getIndex()
	{
		$data['page'] = "Delivery Report";

		return view("member.accounting_transaction.customer.delivery_report.delivery_report_list", $data);
	}
	public function getLoadDeliveryReport()
	{
		$data['_delivery_report'] = [];
		return view("member.accounting_transaction.customer.delivery_report.delivery_report_table", $data);
	}
}