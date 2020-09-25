<?php

namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;


class ReportStockLedgerController extends Member
{
    public function getIndex()
    {
        $data['page'] = 'Stock Ledger';
        return view('.member.reports.accounting.stock_ledger', $data);
    }
}
