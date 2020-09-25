
@extends('member.layout')
@section('content')
<form class="global-submit form-to-submit-transfer" role="form" action="" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="sir_id" value="{{$sir->sir_id}}">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Incoming Load Report &raquo; SIR No {{sprintf("%'.05d\n", $sir->sir_id)}}</span>
                    <small>
                    	ILR 
                    </small>
                </h1>
                
                <div class="text-right">
                    <a class="btn btn-custom-white panel-buttons" href="/member/pis/ilr">Cancel</a>
                    <a size="md" link="/member/pis/ilr/ilr_confirm/{{$sir->sir_id}}/save" class="popup btn btn-primary">Save</a>
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
                    <input type="text" disabled class="datepicker form-control" value="{{date('m/d/Y',strtotime($sir->created_at))}}" name="sir_date">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-1">                                
                    <label>Plate No:</label>
                </div>
                <div class="col-md-4">
                    <select disabled class="form-control select-truck" name="truck_id">
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
                    <select disabled class="form-control select-employee" name="sales_agent_id">
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
                    <div class="row clearfix draggable-container ilr-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 30px;"></th>
                                            <th style="width: 15px;" class="text-right">#</th>
                                            <th style="width: 200px;">Product Name</th>
                                            <th style="width: 200px;">Issued QTY</th>
                                            <th style="width: 200px;">Sold QTY</th>
                                            <th style="width: 200px;">Remaining QTY</th>
                                            <th style="width: 30px;"></th>
                                            <th style="width: 200px;">Physical Count</th>
                                            <th style="width: 200px;">Status</th>
                                            <th style="width: 200px;">Info</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                    @if($_sir_item)
                                        @foreach($_sir_item as $key => $sir_item)                                
                                        <tr class="tr-draggable tr-draggable-html">
                                            <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                            <td class="invoice-number-td text-right">{{$key+1}}</td>
                                            <td>
                                                <label>{{$sir_item->item_name}}</label>
                                            </td>                                            
                                            <td>
                                                <label>{{$sir_item->item_qty}} {{$sir_item->um_abbrev}} </label>
                                            </td>
                                            <td>
                                                <label>{{$sir_item->sold_qty}}</label>
                                            </td>
                                            <td>
                                                <label>
                                                    {{$sir_item->remaining_qty}}
                                                </label>
                                            </td>
                                            <td>
                                                <i size="sm" link="/member/pis/ilr/update_count/{{$sir_item->sir_id}}/{{$sir_item->item_id}}" class="popup btn btn-custom-white fa fa-upload"></i>
                                            </td>
                                            <td>
                                                <input type="text" readonly="true" name="physical[]" value="{{$sir_item->physical_count}}" class="input-sm">
                                            </td>
                                            <td>
                                                <input type="text" readonly="true" name="status[]" value="{{$sir_item->status}}" class="input-sm text-center">
                                            </td>
                                            <td>
                                                <input type="text" name="info[]" readonly="true" style="color: red" value="{{$sir_item->is_updated == 1 ? currency('PHP',$sir_item->infos) : ''}}" class="number-input input-sm">
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
            </div>
            @if($ctr_returns != 0)
            <div class="form-group">                
                <div class="col-md-12">
                    <h4>Empties Returns</h4>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="row clearfix draggable-container empties-container">
                        <div class="table-responsive">
                            <div class="col-sm-12">
                                <table class="digima-table">
                                    <thead >
                                        <tr>
                                            <th style="width: 30px;"></th>
                                            <th style="width: 15px;" class="text-right">#</th>
                                            <th style="width: 200px;">Product Name</th>
                                            <th style="width: 200px;">Returns QTY</th>
                                            <th style="width: 30px;"></th>
                                            <th style="width: 200px;">Physical Count</th>
                                            <th style="width: 200px;">Status</th>
                                            <th style="width: 200px;">Info</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                    @if($_returns)
                                        @foreach($_returns as $keys => $returns)                                
                                        <tr class="tr-draggable tr-draggable-html">
                                            <td class="text-center cursor-move move"><i class="fa fa-th-large colo-mid-dark-gray"></i></td>
                                            <td class="invoice-number-td text-right">{{$keys+1}}</td>
                                            <td>
                                                <label>{{$returns->item_name}}</label>
                                            </td>                                            
                                            <td>
                                                <label>{{$returns->item_count}}</label>
                                            </td>
                                            <td>
                                                <i size="sm" link="/member/pis/ilr/update_count_empties/{{$returns->s_cm_item_id}}" class="popup btn btn-custom-white fa fa-upload"></i>
                                            </td>
                                            <td>
                                                <input type="text" readonly="true" name="physical[]"  value="{{$returns->item_physical_count}}" class="input-sm">
                                            </td>
                                            <td>
                                                <input type="text" readonly="true" name="status[]"  class="input-sm text-center" value="{{$returns->status}}">
                                            </td>
                                            <td>
                                               <input type="text" name="info[]" readonly="true" style="color: red" value="{{$returns->sc_is_updated == 1 ? currency('PHP',$returns->sc_infos) : ''}}" class="number-input input-sm">
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
            </div>
            @endif
        </div>
    </div>
</form>
@endsection


@section('script')
<script type="text/javascript">
    $(".select-truck").globalDropList(
    { 
      hasPopup                : "true",      
      link                    : "/member/pis/truck_list/add",
      link_size               : "md",
      width                   : "100%",
      placeholder             : "Search truck...",
      no_result_message       : "No result found!"
    }).globalDropList("disabled");
    $(".select-employee").globalDropList(
    { 
      hasPopup                : "false",
      width                   : "100%",
      placeholder             : "Search Salesman...",
      no_result_message       : "No result found!"
    }).globalDropList("disabled");
</script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/draggable_row.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/sir.js"></script> -->

@endsection