
@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Purchase Order &raquo; List </span>
                <small>
                    List of Purchase Order
                </small>
            </h1>
            <a class="panel-buttons btn btn-custom-primary pull-right" href="/member/vendor/purchase_order" >Create P.O</a>
        </div>
    </div>
</div>


<!--mai-->
<div class="form-group">
    <div class="col-md-12">
        <div class="col-md-6">
            <ul class="nav nav-tabs">
              <li id="all-list" class="active"><a data-toggle="tab" href="#open"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Open</a></li>

              <li id="archived-list"><a data-toggle="tab" href="#close"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Close</a></li>

              <li id="archived-list"><a data-toggle="tab" href="#all"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;All</a></li>
            </ul>
        </div>
    </div>
</div>
<!--end-->

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
   <!--  <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" onclick="select('all')" data-toggle="tab" href="#all"><i class="fa fa-star"></i> All SIR</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer"  onclick="select(0,0)" data-toggle="tab" href="#open"><i class="fa fa-folder-open"></i> Open SIR</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer"  onclick="select(1,0)" data-toggle="tab" href="#closed"><i class="fa fa-window-close"></i> Closed SIR</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" onclick="select('',1)" data-toggle="tab" href="#archived"><i class="fa fa-trash"></i> Archived SIR</a></li>
    </ul> -->
   <!--  <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_name" placeholder="Search by CM Number" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div> -->
    <div class="form-group tab-content panel-body sir_container">
        <div id="open" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>P.O No</th>
                            <th>Company Name</th>
                            <th>Vendor Name</th>
                            <th>Total</th>
                            <th class="text-center">Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($_po_open)
                        @foreach($_po_open as $po)
                            <tr>
                                <td>{{$po->po_id}}</td>
                                <td>{{$po->vendor_company}}</td>
                                <td>{{$po->vendor_title_name." ".$po->vendor_first_name." ".$po->vendor_middle_name." ".$po->vendor_last_name." ".$po->vendor_suffix_name}}</td>
                                <td>{{currency("PHP",$po->po_overall_price)}}</td>
                                <td class="text-center">
                                    @if($po->po_is_billed == 0)
                                    <a class="btn btn-warning form-control">Open</a>
                                    @else
                                    <a class="btn btn-default form-control">Close</a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                          <!-- <li ><a class="popup" link="/member/customer/view_cm/{{$po->cm_id}}" size="lg">View CM</a></li> -->
                                        <li>
                                            @if($po->po_is_billed == 0)                                            
                                            <a href="/member/vendor/purchase_order?id={{$po->po_id}}">Edit P.O</a>
                                            @endif
                                            <a link="/member/vendor/purchase_order/view_pdf/{{$po->po_id}}" class="popup" size="lg">Print</a>
                                        </li>
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

        <div id="close" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>P.O No</th>
                            <th>Company Name</th>
                            <th>Vendor Name</th>
                            <th>Total</th>
                            <th class="text-center">Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($_po_close)
                        @foreach($_po_close as $po)
                            <tr>
                                <td>{{$po->po_id}}</td>
                                <td>{{$po->vendor_company}}</td>
                                <td>{{$po->vendor_title_name." ".$po->vendor_first_name." ".$po->vendor_middle_name." ".$po->vendor_last_name." ".$po->vendor_suffix_name}}</td>
                                <td>{{currency("PHP",$po->po_overall_price)}}</td>
                                <td class="text-center">
                                    @if($po->po_is_billed == 0)
                                    <a class="btn btn-warning form-control">Open</a>
                                    @else
                                    <a class="btn btn-default form-control">Close</a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                          <!-- <li ><a class="popup" link="/member/customer/view_cm/{{$po->cm_id}}" size="lg">View CM</a></li> -->
                                        <li>
                                            @if($po->po_is_billed == 0)                                            
                                            <a href="/member/vendor/purchase_order?id={{$po->po_id}}">Edit P.O</a>
                                            @endif
                                            <a link="/member/vendor/purchase_order/view_pdf/{{$po->po_id}}" class="popup" size="lg">Print</a>
                                        </li>
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

        <div id="all" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>P.O No</th>
                            <th>Company Name</th>
                            <th>Vendor Name</th>
                            <th>Total</th>
                            <th class="text-center">Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($_po)
                        @foreach($_po as $po)
                            <tr>
                                <td>{{$po->po_id}}</td>
                                <td>{{$po->vendor_company}}</td>
                                <td>{{$po->vendor_title_name." ".$po->vendor_first_name." ".$po->vendor_middle_name." ".$po->vendor_last_name." ".$po->vendor_suffix_name}}</td>
                                <td>{{currency("PHP",$po->po_overall_price)}}</td>
                                <td class="text-center">
                                    @if($po->po_is_billed == 0)
                                    <a class="btn btn-warning form-control">Open</a>
                                    @else
                                    <a class="btn btn-default form-control">Close</a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                          <!-- <li ><a class="popup" link="/member/customer/view_cm/{{$po->cm_id}}" size="lg">View CM</a></li> -->
                                        <li>
                                            @if($po->po_is_billed == 0)                                            
                                            <a href="/member/vendor/purchase_order?id={{$po->po_id}}">Edit P.O</a>
                                            @endif
                                            <a link="/member/vendor/purchase_order/view_pdf/{{$po->po_id}}" class="popup" size="lg">Print</a>
                                        </li>
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