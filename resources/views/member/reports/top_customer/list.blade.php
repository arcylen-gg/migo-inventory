<div class="main-container">
    <div class="report-container">
        <div class="panel panel-default panel-block panel-title-block panel-reportss load-data">
            <div class="panel-heading load-content">
                @include('member.reports.report_header')
                <div class="table-reponsive">
                    <div class="double-scroll">
                        <table class="table table-condensed collaptable table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Code</th>
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-center" nowrap>Customer Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($_top_customer) > 0)
                                    @foreach($_top_customer as $key => $customer)
                                    <tr>
                                        <td class="text-center">{{$key+1}}</td>
                                        <td class="text-center">{{$customer->customer_id}}</td>
                                        <td class="text-left" nowrap>
                                            <strong><span>{{$customer->company}}</span><br>
                                            <small>{{ucfirst($customer->first_name.' '.$customer->middle_name.' '.$customer->last_name.' '.$customer->suffix_name)}}</small></strong>
                                        </td>
                                        <td class="text-right">
                                            {{currency("", $customer->total_sales)}}
                                        </td>
                                    </tr>
                                    @endforeach
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
<style>
    .main-container
    {
        background-color: #fff;
        padding-top: 5px;
    }
    .title
    {
        margin-bottom: 20px;
    }
    .doubleScroll-scroll-wrapper
    {
        width: 100%;
    }
.double-scroll {
width: 100%;
}
</style>