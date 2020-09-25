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
                                            @foreach($shop_payment_method as $payment_method_header)
                                            <th class="text-center" nowrap>{{$payment_method_header->payment_name}}</th>
                                            @endforeach
                                            <th class="text-center" nowrap>SF</th>
                                            <th class="text-center" nowrap>Total</th>
                                            <th class="text-center" nowrap>Receipt No.</th>
                                        </tr>
                                    </thead>
                                    <tbody class="">
                                        @if(count($customer) > 0)
                                            @foreach($customer as $key => $customer_data)
                                            <tr>
                                                <td class="text-left" nowrap>
                                                    {{$customer_data->company}}<br>
                                                    {{ucfirst($customer_data->first_name.' '.$customer_data->middle_name.' '.$customer_data->last_name.' '.$customer_data->suffix_name)}}
                                                </td>
                                                @if(count($customer_data->data) > 0)
                                                    @foreach($customer_data->total_payment as $key_data => $data_payment)
                                                        @if($data_payment != 0)
                                                        <td class="text-right" nowrap>{{currency('',$data_payment - $customer_data->per_customer[$key_data])}} </td>   
                                                        @else
                                                        <td class="text-right" nowrap></td>
                                                        @endif
                                                    @endforeach
                                                @else
                                                <td class="text-right" nowrap></td>
                                                @endif
                                                <td class="text-right" nowrap>{{currency('',$customer_data->total_all_shipping)}}</td>
                                                <td class="text-right" nowrap>{{currency('',$customer_data->total_all - $customer_data->total_all_shipping)}}</td>
                                                <td class="text-center" >
                                                @foreach($customer_data->transaction_ref_num as $key_ref => $transaction_ref_nums)
                                                    {{$transaction_ref_nums->transaction_refnum}}, 
                                                @endforeach
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td class="text-center">TOTAL</td>
                                                @foreach($total_all as $total)
                                                    @if($total != 0)
                                                    <td class="text-right">{{currency('',$total)}}</td>
                                                    @else
                                                    <td class="text-right"></td>
                                                    @endif
                                                @endforeach
                                                <td class="text-right">{{currency('',$total_all_shipping)}}</td>
                                                <td class="text-right">{{currency('',$total_all_customer)}}</td>
                                                <td class="text-right"></td>
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