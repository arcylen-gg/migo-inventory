
@extends('member.layout')
@section('content')
<form class="global-submit form-to-submit-transfer" role="form" action="/member/pis/sir/reload_submit" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" name="sir_id" value="{{$sir->sir_id}}" >
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">SIR &raquo; Reload </span> 
                </h1>
                
                <div class="text-right">
                <a class="btn btn-custom-white panel-buttons" href="/member/pis/lof">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray">
        <div class="panel-body form-horizontal">
        <div class="form-group">
                <div class="col-md-1">                                
                    <label>DATE :</label>
                </div>
                <div class="col-md-4">
                    <input type="text" class="datepicker form-control" disabled name="sir_date" value="{{date('m/d/Y')}}">
                </div>
            </div>
           
            <div class="form-group">
                <div class="col-md-1">                                
                    <label>Plate No:</label>
                </div>
                <div class="col-md-4">
                    <select class="form-control select-truck" disabled name="truck_id">
                        @if($_truck)
                            @foreach($_truck as $truck)
                                <option value="{{$truck->truck_id}}" {{$sir->truck_id == $truck->truck_id ? 'selected' : ''}}>{{$truck->plate_number}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-1">
                    <label>Salesman:</label>
                </div>
                <div class="col-md-4">
                    <select class="form-control select-employee" disabled name="sales_agent_id">
                        @if($_employees)
                            @foreach($_employees as $employee)
                                <option value="{{$employee->employee_id}}" {{$sir->sales_agent_id == $employee->employee_id ? 'selected' : ''}}>{{$employee->first_name}} {{$employee->middle_name}} {{$employee->last_name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>            
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="row clearfix draggable-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 30px;"></th>
                                            <th style="width: 15px;" class="text-right">#</th>
                                            <th style="width: 200px;">Product Code</th>
                                            <th>Description</th>
                                            <th style="width: 200px;">Unit</th>
                                            <th style="width: 70px;">Qty</th>
                                            <th style="width: 120px;">Unit Price</th>
                                            <th style="width: 120px;">Amount</th>
                                            <th width="30"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="draggable">                                       
                                        <tr class="tr-draggable tr-draggable-html">
                                            <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>

                                            <td class="invoice-number-td text-right">1</td>
                                            <td>
                                                <select class="form-control select-item droplist-item input-sm pull-left" name="item[]">
                                                    @include('member.load_ajax_data.load_item_category', ["add_search" => ""]);
                                                </select>
                                            </td>           
                                            <td>
                                                <textarea class="textarea-expand txt-desc" class="txt-desc" name="invline_description[]"></textarea>
                                                <input type="hidden" name="um_qty[]" class="um-qty">
                                            </td>                                 
                                            <td>
                                                <select class="um-list form-control select-item droplist-unit input-sm pull-left" name="related_um_type[]">
                                                </select>
                                            </td>
                                            <td><input class="text-center number-input txt-qty" type="text"  name="item_qty[]"/></td>
                                            <td><input class="text-right number-input txt-rate" type="text" readonly="true" name="item_rate[]"/></td>
                                            <td><input class="text-right number-input txt-amount" type="text" readonly="true" name="item_amount[]"/></td>
                                            <td class="text-center cursor-pointer"><i class="fa fa-trash-o remove-tr" aria-hidden="true"></i></td>
                                        </tr>
                                       
                                        <tr class="tr-draggable">
                                            <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                            <td class="invoice-number-td text-right">2</td>
                                            <td>
                                                <select class="form-control select-item droplist-item input-sm pull-left" name="item[]" >
                                                   @include('member.load_ajax_data.load_item_category', ["add_search" => ""]);
                                                </select>
                                            </td>            
                                            <td>
                                                <textarea class="textarea-expand txt-desc"  class="txt-desc" name="invline_description[]"></textarea>
                                                <input type="hidden" name="um_qty[]" class="um-qty" >
                                            </td>                              
                                            <td>
                                                <select class="um-list form-control select-item droplist-unit input-sm pull-left" name="related_um_type[]" data-placeholder="Select a Customer" style=";">
                                                </select>
                                            </td>
                                            <td><input class="text-center number-input txt-qty" type="text" name="item_qty[]"/></td>
                                            <td><input class="text-right number-input txt-rate" type="text" readonly="true" name="item_rate[]"/></td>
                                            <td><input class="text-right number-input txt-amount" type="text" readonly="true" name="item_amount[]"/></td>
                                            <td class="text-center cursor-pointer"><i class="fa fa-trash-o remove-tr" aria-hidden="true"></i></td>
                                        </tr>

                                    </tbody>
                                   
                                </table>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</form>
<div class="div-script">
    <table class="div-item-row-script hide">
        <tr class="tr-draggable">
            <td class="text-center cursor-move move" ><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
            <td class="invoice-number-td text-right">2</td>
            <td>
                <select class="form-control select-item item-class input-sm pull-left" name="item[]" data-placeholder="Select a Customer" style=";">
                   @include('member.load_ajax_data.load_item_category', ["add_search" => ""]);
                </select>
            </td>
            <td>
                <textarea class="textarea-expand txt-desc"  class="txt-desc" name="invline_description[]"></textarea>
                <input type="hidden" name="um_qty[]" class="um-qty" >
            </td>
            <td>
                <select class="um-list form-control select-item unit-class input-sm pull-left" name="related_um_type[]" >
                </select>
            </td>
            <td><input class="text-center number-input txt-qty" type="text" name="item_qty[]"/></td>
            <td><input class="text-right number-input txt-rate" type="text" readonly="true" name="item_rate[]"/></td>
            <td><input class="text-right number-input txt-amount" type="text" readonly="true" name="item_amount[]"/></td>
            <td class="text-center cursor-pointer"><i class="fa fa-trash-o remove-tr" aria-hidden="true"></i></td>
        </tr>
    </div>
</div>
@endsection


@section('script')
<script type="text/javascript">
// alert($('#server_name').val());
    $(".select-truck").globalDropList(
    { 
      hasPopup                : "true",      
      link                    : "/member/pis/truck_list/add",
      link_size               : "md",
      width                   : "100%",
      placeholder             : "Search truck...",
      no_result_message       : "No result found!"
    })
    $(".select-truck").globalDropList("disabled");

    $(".select-employee").globalDropList(
    { 
      hasPopup                : "false",
      width                   : "100%",
      placeholder             : "Search Salesman...",
      no_result_message       : "No result found!"
    })

    $(".select-employee").globalDropList("disabled");
</script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<script type="text/javascript" src="/assets/member/js/sir.js"></script>
<script type="text/javascript" src="/assets/member/js/prompt_serial_number.js"></script>
@endsection