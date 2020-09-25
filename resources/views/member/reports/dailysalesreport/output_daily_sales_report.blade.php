<div class="wrapper-top-scroll">
    <div class="div-top-scroll">
    </div>
</div>
<div class="wrapper-bottom-scroll">
    <div class="div-bottom-scroll">
        <div class="report-container">
            <div class="wrapper1"><div class="div1"></div></div>
            <div class="wrapper2">
                <div class="div2 table-reponsive" style="width: 100%;">
                    <div class="panel panel-default panel-block panel-title-block panel-reportss load-data" style="width: 100%">
                        <div class="panel-heading load-content">
                            @include('member.reports.report_header_mdY')
                            <div class="table-reponsive">
                                <table class="table table-condensed collaptable table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" nowrap>Name</th>
                                            @foreach($_payment_method as $payment_method_header)
                                            <th class="text-center" nowrap>{{$payment_method_header->payment_name}}</th>
                                            @endforeach
                                            <th class="text-center" nowrap>SF</th>
                                            <th class="text-center" nowrap>Total Sales</th>
                                            <th class="text-center" nowrap>Total</th>
                                            <th class="text-center" nowrap>Payment Applied</th>
                                            <th class="text-center" nowrap>Balance</th>
                                            <th class="text-center" nowrap>Receipt No.</th>
                                        </tr>
                                    </thead>
                                    <tbody {{$total_bal = 0}}>
                                        @if(count($_sales) > 0)
                                            @foreach($_sales as $key => $sales)
                                            <tr>
                                                <td {{$overall_price = 0 }}>{{$sales['customer_name']}}</td>
                                                @foreach($sales['pm_paid'] as $keypm => $pmapplied)
                                                <td  class="text-center" {{$overall_price+= $pmapplied}}>{{$pmapplied != 0 ? currency('', $pmapplied) : '' }}</td>
                                                @endforeach
                                                <td class="text-center">{{$sales['shipping_fee'] != 0 ? currency('',$sales['shipping_fee']) : ''}}</td>
                                                <td class="text-center">{{currency('',$sales['total_sales'] )}}</td>
                                                <td class="text-center">{{currency('',$sales['total'] )}}</td>
                                                <td class="text-center">{{$sales['total_applied'] != 0 ? currency('',$sales['total_applied']) : ''}}</td>
                                                <td class="text-center {{$total_bal += $overall_price - $sales['total_applied']}}">{{currency('',$overall_price - $sales['total_applied'])}}</td>
                                                <td>
                                                    @foreach($sales['_invoice_ref'] as $inv_ref)
                                                    <a target="_blank" href="/member/transaction/{{$inv_ref['is_sales_receipt'] == 1 ? 'sales_receipt' : 'sales_invoice'}}/print?id={{$inv_ref['inv_id']}}">{{$inv_ref['transaction_refnum']}}</a>,
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td class="text-center">TOTAL</td>
                                                @foreach($_pm_total as $pm_total)
                                                <td  class="text-center" {{$pmamount = $pm_total['amount']}} >{{$pmamount != 0 ? currency('', $pmamount) : '' }}</td>
                                                @endforeach
                                                <td class="text-center">{{currency('',$_total_sf)}}</td>
                                                <td class="text-center">{{currency('',$_total_sales)}}</td>
                                                <td class="text-center">{{currency('',$_total)}}</td>
                                                <td class="text-center">{{currency('',$_total_applied)}}</td>
                                                <td class="text-center">{{currency('',$total_bal)}}</td>
                                                <td class="text-center"></td>
                                            </tr>
                                        @else
                                        <tr>
                                            <td class="text-center" colspan="20">NO TRANSACTION YET</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <h5 class="text-center">---- {{$now or ''}} ----</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
.wrapper-top-scroll
{
width: 100%; border: none 0px RED;
overflow-x: scroll; /*overflow-y:hidden;*/
margin: 0 auto;
}
.wrapper-bottom-scroll
{
width: 100%; border: none 0px RED;
overflow-x: scroll; /*overflow-y:hidden;*/
margin: 0 auto;
/*height: 100%; */
}
.div-top-scroll
{
width:130%; height: 20px;
}
.div-bottom-scroll
{
width:130%; /*height: 100%;*//*overflow: auto;*/
}
@page
{
page-break-after: always;
}
</style>