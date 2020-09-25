<div class="wrapper-top-scroll">
    <div class="div-top-scroll">
    </div>
</div>
<div class="wrapper-bottom-scroll">
    <div class="div-bottom-scroll">
        <div class="report-container">
            <div class="panel panel-default panel-block panel-title-block panel-report load-data">
                <div class="panel-heading load-content">
                    @include('member.reports.report_header')
                    <div class="table-reponsive">
                        <table class="table table-condensed collaptable">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                    
                                    @foreach($_account_per_year as $key_per_year =>$account_per_year)
                                    <?php
                                    $income         = collect($account_per_year->_account['Income']['account_details'])->sum('amount');
                                    $cog            = collect($account_per_year->_account['Cost of Goods Sold']['account_details'])->sum('amount');
                                    $expense        = collect($account_per_year->_account['Expense']['account_details'])->sum('amount');
                                    $other_income   = collect($account_per_year->_account['Other Income']['account_details'])->sum('amount');
                                    $other_expense  = collect($account_per_year->_account['Other Expense']['account_details'])->sum('amount');
                                    ?>
                                    <td>
                                        <table class="table table-condensed">
                                            <tr>
                                                <th colspan="2">PROFIT AND LOSS FOR {{$account_per_year->entry_year}}</th>
                                                <td colspan="3"></td>
                                                <th class="text-right">{{currency('PHP', (($income - $cog) - $expense) - ($other_income - $other_expense))}}</th>
                                            </tr>
                                            @foreach($account_per_year->_account as $key=>$account)
                                            <tr>
                                                <td colspan="2" >{{strtoupper($account->chart_type_name)}}</td>
                                                <td colspan="3"></td>
                                                <td class="text-right"><text class="total-report">{{currency('PHP', collect($account->account_details)->sum('amount'))}}</text></td>
                                            </tr>
                                            @foreach($account->account_details as $key1=>$acc_details)
                                            <tr>
                                                <td></td>
                                                <td>{{$acc_details->account_name}}</td>
                                                <td colspan="3"></td>
                                                <td class="text-right">{{currency('PHP', $acc_details->amount)}}</td>
                                            </tr>
                                            @endforeach
                                            @if(count($account->account_details) > 0)
                                            <tr>
                                                <td></td>
                                                <td>Total</td>
                                                <td colspan="3"></td>
                                                <td class="text-right">{{currency('PHP', collect($account->account_details)->sum('amount'))}}</td>
                                            </tr>
                                            @endif
                                            @if($account->chart_type_name == "Cost of Goods Sold")
                                            <tr>
                                                <td colspan="5" >GROSS PROFIT</td>
                                                <td class="text-right">{{currency('PHP', $income - $cog)}}</td>
                                            </tr>
                                            @elseif($account->chart_type_name == "Expense")
                                            <tr>
                                                <td colspan="5" >NET OPERATING INCOME</td>
                                                <td class="text-right">{{currency('PHP', ($income - $cog) - $expense)}}</td>
                                            </tr>
                                            @elseif($account->chart_type_name == "Other Expense")
                                            <tr>
                                                <td colspan="5" >NET OTHER INCOME</td>
                                                <td class="text-right">{{currency('PHP', $other_income - $other_expense)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" >NET INCOME</td>
                                                <td class="text-right"><b>{{currency('PHP', (($income - $cog) - $expense) - ($other_income - $other_expense))}}<b></td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </table>
                                    </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h5 class="text-center">---- {{$now or ''}} ----</h5>
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