@extends('member.layout')
@section('content')

<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Merchant Ewallet - Admin</span>
            </h1>
        </div>
    </div>
</div>
<div class="all_user_body_get">
<div class="col-md-6">
    <div class="panel panel-default panel-block panel-title-block clearfix col-md-12" id="top">
        <div class="panel-body" style="overflow-x:auto;">
            <h3>Merchant List</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            Merchant
                            <br>
                            <span style="color:gray"><small>Info</small></span>
                        </th>
                        <th>
                            Payable
                            <br>
                            <span style="color:gray"><small>E-wallet Transaction</small></span>
                        </th>
                        <th>
                            Requested
                            <br>
                            <span style="color:gray"><small>E-wallet Transaction</small></span>
                        </th>
                        <th>
                            Paid (Verify)
                            <br>
                            <span style="color:gray"><small>E-wallet Transaction</small></span>
                        </th>
                        <th>
                            Denied
                            <br>
                            <span style="color:gray"><small>E-wallet Transaction</small></span>
                        </th>
                        <th>
                            Completed
                            <br>
                            <span style="color:gray"><small>E-wallet Transaction</small></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if($merchant)
                        @foreach($merchant as $key => $value)
                            <tr>
                                <td>{{$value->user_first_name}} {{$value->user_last_name}}

                                </td>
                                <td>
                                    {{currency('PHP', $value->payable)}}
                                    <hr>
                                    <a onclick="view_link('/member/merchant/ewallet/list?user_id={{$value->user_id}}&find=payable')">View List</a>
                                    </td>
                                <td>
                                    {{currency('PHP', $value->Requested)}}
                                    <hr>
                                    <a onclick="view_link('/member/merchant/ewallet/list?user_id={{$value->user_id}}&find=Requested&list=2')">View List</a>
                                </td>
                                <td>
                                    {{currency('PHP', $value->Paid)}}
                                    <hr>
                                    <a onclick="view_link('/member/merchant/ewallet/list?user_id={{$value->user_id}}&find=Paid&list=2')">View List</a>
                                </td>
                                <td>
                                    {{$value->Denied}} Transaction/s
                                    <hr>
                                    <a onclick="view_link('/member/merchant/ewallet/list?user_id={{$value->user_id}}&find=Denied&list=2')">View List</a>
                                </td>
                                <td>
                                    {{currency('PHP', $value->Completed)}}
                                    <hr>
                                    <a onclick="view_link('/member/merchant/ewallet/list?user_id={{$value->user_id}}&find=Completed&list=2')">View List</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="20"><center>--No Merchant Found--</center></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div> 
</div>
<div class="col-md-6 hide div_get_details">
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
</script>
@endsection