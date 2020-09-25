@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Direct Referral PV</span>
                <small>
                    You can set the computation of your direct marketing plan here.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="update_basic_settings()">Update</a>
            <a href="/member/mlm/plan" class="panel-buttons btn btn-custom-white pull-right">  Back</a>
        </div>
    </div>
</div>
{!! $basic_settings !!} 
<!-- <div class="panel panel-default">
  <div class="panel-body">

    <form class="global-submit" method="post" id="save_include" action="/member/mlm/plan/direct_referral_pv/edit/save_include_direct_referral">
        {!! csrf_field() !!}
        <div class="col-md-12 pull">
            <label for="direct_referral_pv_initial_rpv">Include Self Slot to have a pv</label>
            <input type="checkbox" id="direct_referral_pv_initial_rpv" name="direct_referral_pv_initial_rpv" value="1" {{$direct_referral_pv_initial_rpv == 1 ? 'checked' : ''}}>
        </div> 
        <div class="col-md-1 pull-right">
            <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="save_include()">Save</a>
        </div> 
    </form>       

  </div>
</div> -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr><th colspan="3"><center>DIRECT SPONSOR BONUS PER MEMBERSHIP</center></th></tr>
                        <tr>
                            <th>MEMBERSHIP NAME</th>
                            <th>REFERRAL RANK RPV</th>
                            <th>REFERRAL RANK RGPV</th>
                            <th>REFERRAL RANK SPV</th>
                            <th>REFERRAL RANK SGPV</th>
                            <th>REFERRAL RANK SELF RPV</th>
                            <th>REFERRAL RANK SELF SPV</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($membership))
                            @if(!empty($membership))
                                @foreach($membership as $key => $mem)
                                    <tr>
                                        <td>{{$mem->membership_name}}</td>
                                            <form id='form{{$mem->membership_id}}' action='/member/mlm/plan/binary/edit/membership/points' method='post' class='global-submit'>
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="membership_id" value="{{$mem->membership_id}}">
                                                <input type='hidden' class='form-control direct_referral_rpv_container' name='direct_referral_rpv' value=''>
                                                <input type='hidden' class='form-control direct_referral_rgpv_container' name='direct_referral_rgpv' value=''>
                                                <input type='hidden' class='form-control direct_referral_spv_container' name='direct_referral_spv' value=''>
                                                <input type='hidden' class='form-control direct_referral_sgpv_container' name='direct_referral_sgpv' value=''>
                                                <input type='hidden' class='form-control direct_referral_self_rpv_container' name='direct_referral_self_rpv' value=''>
                                                <input type='hidden' class='form-control direct_referral_self_spv_container' name='direct_referral_self_spv' value=''>
                                            </form>
                                            <td>
                                                <span class="direct_referral_rpv{{$mem->membership_id}}">{{$mem->direct_referral_rpv == "" ? 0 : $mem->direct_referral_rpv }}</span>
                                            </td>                                                
                                            <td>
                                                <span class="direct_referral_rgpv{{$mem->membership_id}}">{{$mem->direct_referral_rgpv == "" ? 0 : $mem->direct_referral_rgpv }}</span>
                                            </td>                                                 
                                            <td>
                                                <span class="direct_referral_spv{{$mem->membership_id}}">{{$mem->direct_referral_spv == "" ? 0 : $mem->direct_referral_spv }}</span>
                                            </td>                                                 
                                            <td>
                                                <span class="direct_referral_sgpv{{$mem->membership_id}}">{{$mem->direct_referral_sgpv == "" ? 0 : $mem->direct_referral_sgpv }}</span>
                                            </td>                                                
                                            <td>
                                                <span class="direct_referral_self_rpv{{$mem->membership_id}}">{{$mem->direct_referral_self_rpv == "" ? 0 : $mem->direct_referral_self_rpv }}</span>
                                            </td>                                               
                                            <td>
                                                <span class="direct_referral_self_spv{{$mem->membership_id}}">{{$mem->direct_referral_self_spv == "" ? 0 : $mem->direct_referral_self_spv }}</span>
                                            </td>                                      
                                        <td>
                                            <span class="direct_referral_rpv_edit{{$mem->membership_id}}">
                                                <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_direct_referral_rpv({{$mem->membership_id != null ? $mem->membership_id : '0'}},{{$mem->direct_referral_rpv != null ? $mem->direct_referral_rpv : '0'}},{{$mem->direct_referral_rgpv != null ? $mem->direct_referral_rgpv : '0'}},{{$mem->direct_referral_spv != null ? $mem->direct_referral_spv : '0'}},{{$mem->direct_referral_sgpv != null ? $mem->direct_referral_sgpv : '0'}},{{$mem->direct_referral_self_rpv != null ? $mem->direct_referral_self_rpv : '0'}},{{$mem->direct_referral_self_spv != null ? $mem->direct_referral_self_spv : '0'}})">Edit</a>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="3"><center>No Active Membership</center></td>
                            </tr>
                            @endif
                        @endif
                    </tbody>
                </table> 
            </div>  
        </div>
    </div>
</div>  
@endsection

@section('script')
<script type="text/javascript">
function save_include()
{
 $('#save_include').submit();   
}
function save_direct_points_membership(membershipid)
{
    var direct_referral_rpv      = $('.direct_referral_rpvinput' + membershipid).val();
    var direct_referral_rgpv     = $('.direct_referral_rgpvinput' + membershipid).val();
    var direct_referral_spv      = $('.direct_referral_spvinput' + membershipid).val();
    var direct_referral_sgpv     = $('.direct_referral_sgpvinput' + membershipid).val();
    var direct_referral_self_rpv = $('.direct_referral_self_rpvinput' + membershipid).val();
    var direct_referral_self_spv = $('.direct_referral_self_spvinput' + membershipid).val();
    $(".direct_referral_rpv_container").val(direct_referral_rpv);
    $(".direct_referral_rgpv_container").val(direct_referral_rgpv);
    $(".direct_referral_spv_container").val(direct_referral_spv);
    $(".direct_referral_sgpv_container").val(direct_referral_sgpv);
    $(".direct_referral_self_rpv_container").val(direct_referral_self_rpv);
    $(".direct_referral_self_spv_container").val(direct_referral_self_spv);

    console.log($('#form' + membershipid));
    $('#form' + membershipid).submit();
    cancel(membershipid);
}
function cancel(membershipid)
{
    var direct_referral_rpv = $('.direct_referral_rpvinput' + membershipid).val();
    var direct_referral_rgpv = $('.direct_referral_rgpvinput' + membershipid).val();
    var direct_referral_spv = $('.direct_referral_spvinput' + membershipid).val();
    var direct_referral_sgpv = $('.direct_referral_sgpvinput' + membershipid).val();
    var direct_referral_self_rpv = $('.direct_referral_self_rpvinput' + membershipid).val();
    var direct_referral_self_spv = $('.direct_referral_self_spvinput' + membershipid).val();
    direct_referral_rpv = parseInt(direct_referral_rpv);
    direct_referral_rgpv = parseInt(direct_referral_rgpv);
    direct_referral_spv = parseInt(direct_referral_spv);
    direct_referral_sgpv = parseInt(direct_referral_sgpv);
    direct_referral_self_rpv = parseInt(direct_referral_self_rpv);
    direct_referral_self_spv = parseInt(direct_referral_self_spv);

    // console.log(Number.isInteger(direct_referral_rpv));

    if(Number.isInteger(direct_referral_rpv) == true && Number.isInteger(direct_referral_rgpv) == true && Number.isInteger(direct_referral_spv)  == true &&Number.isInteger(direct_referral_sgpv) == true)
    {
        console.log(direct_referral_rpv);
        $('.direct_referral_rpv' + membershipid).html(direct_referral_rpv);
        $('.direct_referral_rgpv' + membershipid).html(direct_referral_rgpv);
        $('.direct_referral_spv' + membershipid).html(direct_referral_spv);
        $('.direct_referral_sgpv' + membershipid).html(direct_referral_sgpv);
        $('.direct_referral_self_rpv' + membershipid).html(direct_referral_self_rpv);
        $('.direct_referral_self_spv' + membershipid).html(direct_referral_self_spv);
        
        var edit = '<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_direct_referral_rpv('+membershipid+', '+direct_referral_rpv+','+direct_referral_rgpv+','+direct_referral_spv+','+direct_referral_sgpv+','+direct_referral_self_rpv+','+direct_referral_self_spv+')">Edit</a>';
        $('.direct_referral_rpv_edit' + membershipid).html(edit)
    }
}
function edit_direct_referral_rpv(membershipid,direct_referral_rpv,direct_referral_rgpv,direct_referral_spv,direct_referral_sgpv,direct_referral_self_rpv,direct_referral_self_spv)
{
    $('.direct_referral_rpv' + membershipid).html("<input type='number' class='form-control direct_referral_rpvinput"+ membershipid +"' name='direct_referral_rpv' value='"+direct_referral_rpv+"'>");
    $('.direct_referral_rgpv' + membershipid).html("<input type='number' class='form-control direct_referral_rgpvinput"+ membershipid +"' name='direct_referral_rgpv' value='"+direct_referral_rgpv+"'>");
    $('.direct_referral_spv' + membershipid).html("<input type='number' class='form-control direct_referral_spvinput"+ membershipid +"' name='direct_referral_spv' value='"+direct_referral_spv+"'>");
    $('.direct_referral_sgpv' + membershipid).html("<input type='number' class='form-control direct_referral_sgpvinput"+ membershipid +"' name='direct_referral_sgpv' value='"+direct_referral_sgpv+"'>");
    $('.direct_referral_self_rpv' + membershipid).html("<input type='number' class='form-control direct_referral_self_rpvinput"+ membershipid +"' name='direct_referral_self_rpv' value='"+direct_referral_self_rpv+"'>");
    $('.direct_referral_self_spv' + membershipid).html("<input type='number' class='form-control direct_referral_self_spvinput"+ membershipid +"' name='direct_referral_self_spv' value='"+direct_referral_self_spv+"'>");

    $('.direct_referral_rpv_edit' + membershipid).html('<a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="#" onClick="save_direct_points_membership(' + membershipid +')">Save</a>');
    
}
</script>
@endsection
