@extends('member.layout')
@section('content')

<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Merchant Ewallet</span>
            </h1>
        </div>
    </div>
</div>
<div class="all_user_body_get">
<div class="col-md-4">
    <div class="panel panel-default panel-block panel-title-block clearfix col-md-12" id="top">
        <div class="panel-body" style="overflow-x:auto;">
            <h3>Transaction from E-wallet Receivables</h3>
            <table class="table table-bordered">
                <tr>
                    <td>Receivables</td>
                    <td>{{currency('PHP', $recievable)}}</td>
                    <td><a onClick="view_link('/member/merchant/ewallet/list?user_id={{$user_id}}&find=payable')">Lists of Receivables</a></td>
                </tr>
                <tr>    
                    <td>Requested</td>
                    <td>{{currency('PHP', $Requested)}} </td>
                    <td>
                        <a onClick="view_link('/member/merchant/ewallet/list?user_id={{$user_id}}&find=Requested&list=2')">Lists of Requested</a>
                        <hr><a onClick="view_link('/member/merchant/ewallet/request?user_id={{$user_id}}')">Request For Collection</a>
                    </td>
                </tr>
                <tr>
                    <td>Paid</td>
                    <td>{{currency('PHP', $Paid)}} </td>
                    <td><a onClick="view_link('/member/merchant/ewallet/list?user_id={{$user_id}}&find=Paid&list=2')">Lists of Paid</a></td>
                </tr>
                <tr>
                    <td>Denied</td>
                    <td>{{$Denied}} Transaction </td>
                    <td><a onClick="view_link('/member/merchant/ewallet/list?user_id={{$user_id}}&find=Denied&list=2')">Lists of Denied</a></td>
                </tr>
                <tr>
                    <td>Completed</td>
                    <td>{{currency('PHP', $Completed)}} </td>
                    <td><a onClick="view_link('/member/merchant/ewallet/list?user_id={{$user_id}}&find=Completed&list=2')">Lists of Completed</a></td>
                </tr>
            </table>
        </div>
    </div> 
</div>
<div class="col-md-8 hide div_get_details">
    <div class="panel panel-default panel-block panel-title-block clearfix col-md-12" id="top">
        <div class="panel-body">
            <div class="div_get_details_body" style="overflow-x:auto;">
                
            </div>
        </div>
    </div> 
</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
function show_loader(div)
{
    $('.' + div).html('<center><i class="fa fa-spinner fa-spin" style="font-size:32px"></i></center>');
}
function view_link(link)
{
    $('.div_get_details').removeClass('hide');
    show_loader('div_get_details_body');
    $('.div_get_details_body').load(link);
}
function check_commission_range(user_id)
{
    var request_from = $('.request_from').val();
    var request_to = $('.request_to').val();
    if(!request_from){ alert('From Date is required'); return; }
    if(!request_to){ alert('To Date is required'); return;}
    if(request_from >= request_to){ alert('To must be greater than from'); return; }

    var q_string = '/member/merchant/ewallet/request/verfiy?request_from=' + request_from + '&request_to=' + request_to + '&user_id=' +user_id;
    show_loader('verify_commision_div');
    $('.verify_commision_div').load(q_string);
}
</script>
@endsection