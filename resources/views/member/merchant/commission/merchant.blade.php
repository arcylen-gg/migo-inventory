@extends('member.layout')
@section('content')

<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Merchant Commission - Merchant</span>
            </h1>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="panel panel-default panel-block panel-title-block clearfix" id="top">
        <div class="panel-body">
            <h3>Summary</h3>
            <table class="table table-bordered">
                
                <tbody>
                    <tr>
                        <td>Payable Commission</td>
                        <td>{{currency('PHP', $payable)}}</td>
                        <td><a onClick="view_link('/member/merchant/commission/user/{{$user_id}}?commission=collectable')">Breakdown</a></td>
                    </tr>
                    <tr>
                        <td>Requested(Payment) Commission</td>
                        <td>{{currency('PHP', $requested)}}</td>
                        <td><a onClick="view_link('/member/merchant/commission/user/{{$user_id}}?commission=requested')">Breakdown</a></td>
                    </tr>
                    <tr>
                        <td>Paid(Need to be verified) Commission</td>
                        <td>{{currency('PHP', $paid)}}</td>
                        <td><a onClick="view_link('/member/merchant/commission/user/{{$user_id}}?commission=paid')">Breakdown</a></td>
                    </tr>
                    <tr>
                        <td>Collected Commission</td>
                        <td>{{currency('PHP', $collected)}}</td>
                        <td><a onClick="view_link('/member/merchant/commission/user/{{$user_id}}?commission=collected')">Breakdown</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div> 
</div>
<div class="col-md-6 hide div_get_details">
    <div class="panel panel-default panel-block panel-title-block clearfix col-md-12" id="top">
        <div class="panel-body ">
            <div class="div_get_details_body" style="overflow-x:auto;">
                
            </div>
        </div>
    </div> 
</div>
@endsection

@section('script')
<script type="text/javascript">
    function view_link(link)
    {
        $('.div_get_details').removeClass('hide');
        show_loader('div_get_details_body');
        $('.div_get_details_body').load(link);
    }
    function show_loader(div)
    {
        $('.' + div).html('<center><i class="fa fa-spinner fa-spin" style="font-size:32px"></i></center>');
    }
</script>
@endsection