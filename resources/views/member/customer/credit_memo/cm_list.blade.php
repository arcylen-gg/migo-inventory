@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Credit Memo</span>
                <small>
                    List of Customer Credit Memo
                </small>
            </h1>
            <a class="panel-buttons btn btn-custom-primary pull-right" href="/member/customer/credit_memo" >Create Credit Memo</a>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
   <!--  <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" onclick="select('all')" data-toggle="tab" href="#all"><i class="fa fa-star"></i> All SIR</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer"  onclick="select(0,0)" data-toggle="tab" href="#open"><i class="fa fa-folder-open"></i> Open SIR</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer"  onclick="select(1,0)" data-toggle="tab" href="#closed"><i class="fa fa-window-close"></i> Closed SIR</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" onclick="select('',1)" data-toggle="tab" href="#archived"><i class="fa fa-trash"></i> Archived SIR</a></li>
    </ul> -->
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_name" placeholder="Search by CM Number" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    <div class="form-group tab-content panel-body sir_container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>CM No</th>
                            <th>Customer Name</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($_cm) > 0)
                            @foreach($_cm as $cm)
                                @if($cm->credit_memo_id == 0)
                                <tr>
                                    <td>{{$cm->cm_id}}</td>
                                    <td>
                                        {{$cm->company}} <br>
                                        <small> {{$cm->title_name." ".$cm->first_name." ".$cm->middle_name." ".$cm->last_name." ".$cm->suffix_name}}</small>
                                    </td>
                                    <td>{{currency("PHP",$cm->cm_amount)}}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                          <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-menu-custom">
                                              <!-- <li ><a class="popup" link="/member/customer/view_cm/{{$cm->cm_id}}" size="lg">View CM</a></li> -->
                                            @if($cm->cm_used_ref_name == "others")
                                              <li ><a class="popup" link="/member/customer/credit_memo/update_action?type=invoice&cm_id={{$cm->cm_id}}" size="lg">Apply to Invoice</a></li>
                                            @endif
                                            @if($cm->manual_cm_id == null)
                                              <li ><a href="/member/customer/credit_memo?id={{$cm->cm_id}}">Edit CM</a></li>
                                            @endif
                                          </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        @else
                            <tr><td colspan="4" class="text-center">NO PROCESS YET</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    
</div>
@endsection