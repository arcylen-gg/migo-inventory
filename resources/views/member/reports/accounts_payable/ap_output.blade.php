<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
                <table class="table table-condensed collaptable">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th width="20px">CODE</th>
                            <th >VENDOR NAME</th>
                            <th class="text-center">TRANSACTION DATE</th>
                            <th class="text-center">AMOUNT</th>
                            <th class="text-center">BALANCE</th>
                            <th class="text-center">REFERENCE NUMBER</th>
                            <th class="text-center" width="200px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($_vendor_ap) > 0)
                            @foreach($_vendor_ap as $key => $ap)
                            <tr>
                                <td>{{$ap->vendor_id}}</td>
                                <td class="text-left" nowrap>
                                    <strong><span>{{$ap->vendor_company}}</span><br>
                                    <small>{{ucfirst($ap->vendor_first_name.' '.$ap->vendor_middle_name.' '.$ap->vendor_last_name.' '.$ap->vendor_suffix_name)}}</small></strong>
                                </td>
                                <td class="text-center">{{date("F d, Y",strtotime($ap->bill_date))}}</td>
                                <td class="text-right">{{currency("", $ap->bill_total_amount)}}</td>
                                <td class="text-right">{{currency("", $ap->vendor_payable)}}</td>
                                <td class="text-center">{{$ap->transaction_refnum}}</td>
                                <td class="text-center"><a target="_blank" href="/member/transaction/pay_bills/create?eb_id={{$ap->bill_id}}">Pay Bills</a></td>
                            </tr>
                            @endforeach
                        @else
                        <tr><td colspan="6" class="text-center">NO PAYABLES</td></tr>
                        @endif                                
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>