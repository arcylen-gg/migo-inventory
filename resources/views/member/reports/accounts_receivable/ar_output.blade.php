<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
                <table class="table table-condensed collaptable">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th width="20px">CODE</th>
                            <th >CUSTOMER NAME</th>
                            <th class="text-center">TRANSACTION DATE</th>
                            <th class="text-center">AMOUNT</th>
                            <th class="text-center">BALANCE</th>
                            <th class="text-center">REFERENCE NUMBER</th>
                            <th class="text-center" width="200px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($_customer_ar) > 0)
                            @foreach($_customer_ar as $key => $ar)
                            <tr>
                                <td>{{$ar->customer_id}}</td>
                                <td class="text-left" nowrap>
                                    <strong><span>{{$ar->company}}</span><br>
                                    <small>{{ucfirst($ar->first_name.' '.$ar->middle_name.' '.$ar->last_name.' '.$ar->suffix_name)}}</small></strong>
                                </td>
                                <td class="text-center">{{date("F d, Y",strtotime($ar->inv_date))}}</td>
                                <td class="text-right">{{currency("", $ar->inv_overall_price)}}</td>
                                <td class="text-right">{{$ar->inv_balance < 0 ? "(".number_format(abs($ar->inv_balance), 2).")" : number_format($ar->inv_balance, 2) }}</td>
                                <td class="text-center">{{$ar->transaction_refnum}}</td>
                                <td class="text-center {{$report_type == 'plain' || !$report_type ? '' : 'hidden'}}"><a target="_blank" href="/member/transaction/receive_payment/create?si_id={{$ar->inv_id}}">Receive Payment</a></td>
                            </tr>
                            @endforeach
                        @else
                        <tr><td colspan="6" class="text-center">NO RECEIVABLES</td></tr>
                        @endif                                
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>