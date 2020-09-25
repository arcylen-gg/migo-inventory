@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Incoming Load Report &raquo; Unprocessed I.L.R <strong class="blink-me" style="color: green">Total: {{number_format($count_sir)}} </strong></span>
                <small>
                    List of I.L.R
                </small>
            </h1>
            <!-- <a class="panel-buttons btn btn-custom-primary pull-right" href="/member/pis/sir/create">Create S.I.R.</a> -->
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" onclick="select('all')" data-toggle="tab" href="#all"><i class="fa fa-star"></i> All ILR</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer"  onclick="select(1)" data-toggle="tab" href="#open"><i class="fa fa-folder-open"></i> Open ILR</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer"  onclick="select(2)" data-toggle="tab" href="#closed"><i class="fa fa-window-close"></i> Closed ILR</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_name" id="srch_sir_id" placeholder="Search by SIR Number" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    <div class="form-group tab-content panel-body sir_container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="load-data" target="ilr_data" >
                <div id="ilr_data">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th>SIR No</th>
                                    <th>SIR Created</th>
                                    <th>Truck Plate No</th>
                                    <th>Sales Agent</th>
                                    <th>Total Item</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @if($_sir)
                                   @foreach($_sir as $sir)
                                        <tr>
                                            <td align="center">{{sprintf("%'.05d\n", $sir->sir_id)}}</td>
                                            <td>{{date('F d, Y', strtotime($sir->sir_created))}}</td>
                                            <td>{{$sir->plate_number}}</td>
                                            <td>{{$sir->first_name}} {{$sir->middle_name}} {{$sir->last_name}}</td>
                                            <td>{{$sir->total_item}}</td>
                                            <td>{{currency("PHP",$sir->total_amount)}}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                  <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                  </button>
                                                  @if($sir->sir_archived == 1)
                                                    <ul class="dropdown-menu dropdown-menu-custom">
                                                        <li><a size="md" link="/member/pis/sir/{{$sir->sir_id}}/restore" class="popup">Restore SIR</a></li>
                                                    </ul>
                                                  @else
                                                      <ul class="dropdown-menu dropdown-menu-custom">
                                                       <li><a size="lg" link="/member/pis/sir/view_status/{{$sir->sir_id}}" class="popup">View Status</a></li>
                                                      @if($sir->sir_status == 0)
                                                        <li><a size="lg" link="/member/pis/sir/view/{{$sir->sir_id}}/sir" class="popup">View SIR</a></li>
                                                        <li><a href="/member/pis/sir/edit/{{$sir->sir_id}}">Edit SIR</a></li>
                                                        <li><a size="md" link="/member/pis/sir/{{$sir->sir_id}}/archived" class="popup">Archive SIR</a></li>
                                                      @elseif($sir->ilr_status == 1)                                          
                                                        <li><a size="lg" link="/member/pis/sir/view/{{$sir->sir_id}}/sir" class="popup">View SIR</a></li>
                                                        <li><a href="/member/pis/ilr/{{$sir->sir_id}}">Processed ILR</a></li>
                                                        <li><a size="lg" link="/member/pis/ilr/view/{{$sir->sir_id}}" class="popup">View ILR</a></li>
                                                      @elseif($sir->ilr_status == 2)                                         
                                                        <li><a size="lg" link="/member/pis/ilr/view/{{$sir->sir_id}}" class="popup">View ILR</a></li>
                                                      @endif
                                                      </ul>
                                                  @endif
                                                </div>
                                            </td>
                                        </tr>
                                   @endforeach
                               @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center pull-right">
                        {!!$_sir->render()!!}
                    </div> 
                </div>
            </div>
        </div>
    </div>
    
    
</div>
<style type="text/css">
    .blink-me
    {
      animation: blinker 1s linear infinite;
    }

    @keyframes blinker
    {  
      50% { opacity: 0; }
    }
</style>
@endsection
@section("script")
<script type="text/javascript">
    var status_s = 'all';
    $("#srch_sir_id").keyup(function()
    {
        select(status_s,$(this).val());
    });
    function select(status = '',srch_id = '')
    {
        $(".modal-loader").removeClass("hidden");
        status_s = status;
        $(".sir_container").load("/member/pis/ilr?status="+status + "&sir_id="+srch_id+" .sir_container", function()
        {
            $(".modal-loader").addClass("hidden");
            $(".load-data").attr("status",status);
            $(".load-data").attr("sir_id",srch_id);

        });
    }
</script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection