@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Check &raquo; List </span>
                <small>
                    List of Check
                </small>
            </h1>
            <a class="panel-buttons btn btn-custom-primary pull-right" href="/member/vendor/write_check" >Write Check</a>
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
    <!-- <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_name" placeholder="Search by SIR Number" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div> -->
    <div class="form-group tab-content panel-body sir_container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Check No</th>
                            <th>Name</th>
                            <th>Total</th>
                            <th>Category Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($_check)
                        @foreach($_check as $check)
                            <tr>
                                <td>{{$check->wc_id}}</td>
                                <td>{{$check->name}}</td>
                                <td>{{currency("PHP",$check->wc_total_amount)}}</td>
                                <td>
                                    @if($check->wc_ref_name == "paybill")
                                    Bill Payment (Check)
                                    @else
                                    Check
                                    @endif                                    
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                        @if($check->wc_ref_name == "paybill")
                                          <li><a href="/member/vendor/paybill?id={{$check->wc_ref_id}}">Edit Bill Payment(Check)</a></li>
                                        @else
                                          <li><a href="/member/vendor/write_check?id={{$check->wc_id}}">Edit Check</a></li>
                                        @endif
                                      </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection