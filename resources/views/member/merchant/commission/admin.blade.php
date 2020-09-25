@extends('member.layout')
@section('content')

<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Merchant Commission - Admin</span>
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
                            Collectibles
                            <br>
                            <span style="color:gray"><small>Commission</small></span>
                        </th>
                        <th>
                            Requested for collection
                            <br>
                            <span style="color:gray"><small>Commission</small></span>
                        </th>
                        <th>
                            Paid (Need to verify)
                            <br>
                            <span style="color:gray"><small>Commission</small></span>
                        </th>
                        <th>
                            Collected
                            <br>
                            <span style="color:gray"><small>Commission</small></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if($merchant)
                        @foreach($merchant as $key => $value)
                            <tr>
                                <td>{{$value->user_first_name}} {{$value->user_last_name}}</td>
                                <td>
                                    {{currency('PHP', $value->collectable)}}<hr>
                                    <span style="color:gray">
                                        <small>
                                            <div class="col-md-12"><a class="view_link" link="/member/merchant/commission/user/{{$value->user_id}}?commission=collectable">View List of Collectibles</a></div>
                                        </small>
                                    </span>
                                </td>
                                <td>
                                    {{currency('PHP', $value->collectable_requested)}}
                                    <hr>
                                    <span style="color:gray">
                                        <small>
                                            <a class="view_link" link="/member/merchant/commission/user/{{$value->user_id}}?commission=requested">View List of Requested for Collection</a>
                                            <hr>
                                            <a class="view_link" link="/member/merchant/commission/request?user_id={{$value->user_id}}">Request for a new collection</a>
                                        </small>
                                    </span>
                                </td>
                                <td>
                                    {{currency('PHP', $value->collectable_paid)}}<hr>
                                    <span style="color:gray">
                                        <small>
                                            <div class="col-md-12"><a class="view_link" link="/member/merchant/commission/user/{{$value->user_id}}?commission=paid">View List of Paid  Collection</a></div>
                                        </small>
                                    </span>
                                </td>
                                <td>
                                    {{currency('PHP', $value->collectable_approved)}}<hr>
                                    <span style="color:gray">
                                        <small>
                                            <div class="col-md-12"><a class="view_link" link="/member/merchant/commission/user/{{$value->user_id}}?commission=collected">View List of Collected</a></div>
                                        </small>
                                    </span>
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
    var old_link = '';
    $('.view_link').on('click', function(){
        $('.div_get_details').removeClass('hide');
        var link = $(this).attr('link');
        show_loader('div_get_details_body');
        $('.div_get_details_body').load(link);
    });
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

        var q_string = '/member/merchant/commission/request/range/verfiy?request_from=' + request_from + '&request_to=' + request_to + '&user_id=' +user_id;
        show_loader('verify_commision_div');
        $('.verify_commision_div').load(q_string);
    }
    function submit_done(data)
    {
        if(data.status =='warning')
        {
            toastr.warning(data.message);
        } 
        else if(data.status =='success')
        {
            toastr.success(data.message);
            $('.all_user_body_get').load('/member/merchant/commission .all_user_body_get');
            location.reload();
        }
    }
</script>
@endsection