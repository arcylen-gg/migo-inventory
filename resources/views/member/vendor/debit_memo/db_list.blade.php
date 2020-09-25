@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Debit Memo</span>
                <small>
                    List of Vendor Debit Memo
                </small>
            </h1>
            @if($debit_memo)
            <a class="panel-buttons btn btn-custom-primary pull-right" href="/member/vendor/debit_memo">Create Debit Memo</a>
            @endif
            @if($bad_order)
            <a class="panel-buttons btn btn-custom-white pull-right" href="/member/vendor/debit_memo?type=bad_order">Create Bad Order</a>
            @endif
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
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_name" placeholder="Search by db Number" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    <div class="form-group tab-content panel-body sir_container">
        <div id="open" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Debit Memo No</th>
                            <th>Vendor Name</th>
                            <th>Total</th>
                            @if($pis != 0)
                            <th>Type</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($_db_open)
                        @foreach($_db_open as $db)
                            <tr>
                                <td>{{$db->db_id}}</td>
                                <td>{{$db->vendor_company or $db->vendor_title_name." ".$db->vendor_first_name." ".$db->vendor_middle_name." ".$db->vendor_last_name." ".$db->vendor_suffix_name}}</td>
                                <td>{{currency("PHP",$db->db_amount)}}</td>
                                @if($pis != 0)
                                <td class="{{$db->is_bad_order == 1 ? $type='Bad Order' : $type='Debit Memo'}}">
                                    {{$type or ''}}
                                </td>
                                @endif
                                <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                              <li><a link="/member/vendor/debit_memo/db_view_pdf/{{$db->db_id}}" class="popup" size="lg">Print</a></li>
                                        @if($db->is_bad_order == 1)
                                            @if($db->db_memo_status != 1)
                                              <li><a href="/member/vendor/debit_memo?id={{$db->db_id}}">Edit {{$type or 'Debit Memo'}}</a></li>
                                              <li><a href="/member/vendor/debit_memo/replace/{{$db->db_id}}">Replace</a></li>
                                              <li><a class="popup" size="md" link="/member/vendor/debit_memo/confirm_condemned/{{$db->db_id}}/Condemned">Condemned</a></li>
                                            @else
                                              <li>
                                                 <a href="#">CLOSED</a>
                                              </li>
                                              <li><a href="/member/vendor/debit_memo/replace/{{$db->db_id}}">Edit Condemned</a></li>
                                            @endif
                                        @else
                                            <li><a href="/member/vendor/debit_memo?id={{$db->db_id}}">Edit {{$type or "Debit Memo"}}</a></li>
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
        <div id="close" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Debit Memo No</th>
                            <th>Vendor Name</th>
                            <th>Total</th>
                            @if($pis != 0)
                            <th>Type</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($_db_close)
                        @foreach($_db_close as $db)
                            <tr>
                                <td>{{$db->db_id}}</td>
                                <td>{{$db->vendor_company or $db->vendor_title_name." ".$db->vendor_first_name." ".$db->vendor_middle_name." ".$db->vendor_last_name." ".$db->vendor_suffix_name}}</td>
                                <td>{{currency("PHP",$db->db_amount)}}</td>
                                @if($pis != 0)
                                <td class="{{$db->is_bad_order == 1 ? $type='Bad Order' : $type='Debit Memo'}}">
                                    {{$type or ''}}
                                </td>
                                @endif
                                <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                              <li><a link="/member/vendor/debit_memo/db_view_pdf/{{$db->db_id}}" class="popup" size="lg">Print</a></li>
                                        @if($db->is_bad_order == 1)
                                            @if($db->db_memo_status != 1)
                                              <li><a href="/member/vendor/debit_memo?id={{$db->db_id}}">Edit {{$type or 'Debit Memo'}}</a></li>
                                              <li><a href="/member/vendor/debit_memo/replace/{{$db->db_id}}">Replace</a></li>
                                              <li><a class="popup" size="md" link="/member/vendor/debit_memo/confirm_condemned/{{$db->db_id}}/Condemned">Condemned</a></li>
                                            @else
                                              <li>
                                                 <a href="#">CLOSED</a>
                                              </li>
                                              <li><a href="/member/vendor/debit_memo/replace/{{$db->db_id}}">Edit Condemned</a></li>
                                            @endif
                                        @else
                                            <li><a href="/member/vendor/debit_memo?id={{$db->db_id}}">Edit {{$type or "Debit Memo"}}</a></li>
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
        <div id="all" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Debit Memo No</th>
                            <th>Vendor Name</th>
                            <th>Total</th>
                            @if($pis != 0)
                            <th>Type</th>
                            @endif
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($_db)
                        @foreach($_db as $db)
                            <tr>
                                <td>{{$db->db_id}}</td>
                                <td>{{$db->vendor_company or $db->vendor_title_name." ".$db->vendor_first_name." ".$db->vendor_middle_name." ".$db->vendor_last_name." ".$db->vendor_suffix_name}}</td>
                                <td>{{currency("PHP",$db->db_amount)}}</td>
                                @if($pis != 0)
                                <td class="{{$db->is_bad_order == 1 ? $type='Bad Order' : $type='Debit Memo'}}">
                                    {{$type or ''}}
                                </td>
                                @endif
                                <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                              <li><a link="/member/vendor/debit_memo/db_view_pdf/{{$db->db_id}}" class="popup" size="lg">Print</a></li>
                                        @if($db->is_bad_order == 1)
                                            @if($db->db_memo_status != 1)
                                              <li><a href="/member/vendor/debit_memo?id={{$db->db_id}}">Edit {{$type or 'Debit Memo'}}</a></li>
                                              <li><a href="/member/vendor/debit_memo/replace/{{$db->db_id}}">Replace</a></li>
                                              <li><a class="popup" size="md" link="/member/vendor/debit_memo/confirm_condemned/{{$db->db_id}}/Condemned">Condemned</a></li>
                                            @else
                                              <li>
                                                 <a href="#">CLOSED</a>
                                              </li>
                                              <li><a href="/member/vendor/debit_memo/replace/{{$db->db_id}}">Edit Condemned</a></li>
                                            @endif
                                        @else
                                            <li><a href="/member/vendor/debit_memo?id={{$db->db_id}}">Edit {{$type or "Debit Memo"}}</a></li>
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