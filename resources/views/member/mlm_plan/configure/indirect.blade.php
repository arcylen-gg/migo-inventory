@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Marketing Plan - Indirect</span>
                <small>
                    You can set the computation of your indirect marketing plan here.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-custom-blue pull-right" onClick="update_basic_settings()">Update</a>
            <a href="/member/mlm/plan" class="panel-buttons btn btn-custom-white pull-right"> < Back</a>
        </div>
    </div>
</div>
{!! $basic_settings !!}
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr><th colspan="3"><center>INDIRECT LEVEL BONUS PER MEMBERSHIP</center></th></tr>
                        <tr>
                            <th>MEMEBERSHIP NAME</th>
                            <th>NUMBER OF LEVELS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($membership))
                            @if(!empty($membership))
                                @foreach($membership as $key => $mem)
                                    <tr class="tr_to_appen_indirect_per_membership{{$mem->membership_id}}">
                                        <td>{{$mem->membership_name}}</td>
                                        <td>{{$mem->indirect_count}}</td>
                                        <td>
                                            <a data-toggle="tooltip" data-placement="left" title="Tooltip on left" href="javascript:" onClick="edit_indirect_membership({{$mem->membership_id}})">Edit</a>
                                            
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
function edit_indirect_membership(membership_id)
{
    $('.tr_to_appen_indirect_per_membership'+membership_id).html('<td colspan="3"><center><div style="margin: 100px auto;" class="loader-16-gray"></div></center></td>');
    $('.tr_to_appen_indirect_per_membership'+membership_id).load("/member/mlm/plan/indirect/edit/settings/" + membership_id);
}
</script>
@endsection
