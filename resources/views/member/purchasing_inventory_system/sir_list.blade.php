@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Load Out Form</span>
                <small>
                    List of LOF
                </small>
            </h1>
            <a class="panel-buttons btn btn-custom-primary pull-right" href="/member/pis/lof/create" >Create Load Out Form</a>
        </div>
    </div>
</div>
<!-- TEMPORARY -->
<!-- <div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div class="form-group">
            <div class="col-md-4">
                <a link="/member/pis/sir/confirm_sync/import" size="md" class="popup btn btn-primary">Sync Import Data</a>
            </div>
            <div class="col-md-4">
                <a link="/member/pis/sir/confirm_sync/export" size="md" class="popup btn btn-primary">Sync Export Data</a>
            </div>
        </div>
    </div>
</div> -->
<!-- END TEMPORARY -->

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer all-sir"><a class="cursor-pointer" onclick="select('all')" data-toggle="tab" href="#all"><i class="fa fa-star"></i> All</a></li>
        <li class="cursor-pointer sir-class"><a class="cursor-pointer"  onclick="select('notall',1,0,0,0)" data-toggle="tab" href="#new"><i class="fa fa-reorder"></i> STEP 1: Load Out Form </a></li>
        <li class="cursor-pointer sir-class"><a class="cursor-pointer"  onclick="select('notall',3,0,0,0)" data-toggle="tab" href="#new"><i class="fa fa-reorder"></i> Rejected LOF </a></li>
        <li class="cursor-pointer sir-class"><a class="cursor-pointer"  onclick="select('notall',2,1,0,0)" data-toggle="tab" href="#new"><i class="fa fa-reorder"></i> STEP 2: Convert To SIR</a></li>
        <li class="cursor-pointer sir-class"><a class="cursor-pointer"  onclick="select('notall',2,1,0,1)" data-toggle="tab" href="#confirmed"><i class="fa fa-check"></i> STEP 3: S.I.R </a></li>
        <li class="cursor-pointer sir-class"><a class="cursor-pointer"  onclick="select('notall',2,2,1,1)" data-toggle="tab" href="#rejected"><i class="fa fa-close"></i>STEP 4: Open Incoming Load Report </a></li>
        <li class="cursor-pointer sir-class"><a class="cursor-pointer" onclick="select('notall',2,2,2,1)" data-toggle="tab" href="#archived"><i class="fa fa-trash"></i> STEP 5 : Closed Incoming Load Report</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_name" id="srch_sir_id" placeholder="Search by SIR number" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    <div class="form-group tab-content panel-body sir_container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="load-data" target="lof_data" >
                <div id="lof_data">
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
                                    <th>STATUS</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @if($_sir != null)
                                   @foreach($_sir as $sir)
                                        <tr>
                                            <td align="center">{{$sir->sir_id}}</td>
                                            <td>{{date('F d, Y', strtotime($sir->sir_created))}}</td>
                                            <td>{{$sir->plate_number}}</td>
                                            <td>{{$sir->first_name}} {{$sir->middle_name}} {{$sir->last_name}}</td>
                                            <td>{{$sir->total_item}}</td>
                                            <td>{{currency("PHP",$sir->total_amount)}}</td>
                                            <td>
                                                <button style="width: 100%" class="btn btn-{{$sir->status_color}}" >{{$sir->status}}</button>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                  <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action <span class="caret"></span>
                                                  </button>
                                                    <ul class="dropdown-menu dropdown-menu-custom"> 
                                                  @if($sir->sir_archived == 1)
                                                        <li><a size="md" link="/member/pis/sir/{{$sir->sir_id}}/restore" class="popup">Restore SIR</a></li>
                                                  @else
                                                       <li><a size="lg" link="/member/pis/sir/view_status/{{$sir->sir_id}}" class="popup">View Status</a></li>
                                                      @if($sir->sir_status == 1)
                                                      @elseif($sir->lof_status == 2)
                                                        <li><a size="md" link="/member/pis/sir/open/{{$sir->sir_id}}/open" class="popup">Open SIR</a></li>
                                                      @elseif($sir->lof_status == 3)
                                                        <li><a size="lg" link="/member/pis/sir/view/{{$sir->sir_id}}/lof" class="popup">View Load Out Form</a></li>
                                                        <li><a href="/member/pis/lof/edit/{{$sir->sir_id}}">Edit Load Out Form</a></li>
                                                        <li><a size="md" link="/member/pis/lof/{{$sir->sir_id}}/archived" class="popup">Archive L.O.F</a></li>
                                                      @endif

                                                    </ul>
                                                  @endif
                                                </div>
                                            </td>
                                        </tr>
                                   @endforeach                      
                               @endif
                              <!--  <tr>
                                   <td colspan="7" class="text-center"><strong>No Data Found...</strong></td>
                               </tr> -->
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
@endsection
@section("script")
<script type="text/javascript">
    var status_s = 'all';
    var lof_status_s = 0;
    var sir_status_s = 0;
    var ilr_status_s = 0;
    var sync_s = 0;
    $("#srch_sir_id").keyup(function()
    {
        select(status_s, lof_status_s, sir_status_s, ilr_status_s, sync_s, $(this).val());
        $(".load-data").attr("sir_id",$(this).val());
    });
    function select(status,lof_status,sir_status,ilr_status,sync,sir_id = '')
    {        
        $(".modal-loader").removeClass("hidden");
        status_s = status;
        lof_status_s = lof_status;
        sir_status_s = sir_status;
        ilr_status_s = ilr_status;
        sync_s = sync;

        $(".sir_container").load("/member/pis/sir?status="+status +"&lof_status="+lof_status +"&sir_status="+sir_status+"&ilr_status="+ilr_status +"&sync="+sync + "&sir_id="+sir_id + " .sir_container", function()
            {
                $(".modal-loader").addClass("hidden");
                $(".load-data").attr("status",status);
                $(".load-data").attr("lof_status",lof_status);
                $(".load-data").attr("sir_status",sir_status);
                $(".load-data").attr("ilr_status",ilr_status);
                $(".load-data").attr("sync",sync);
                $(".load-data").attr("sir_id",sir_id);
            });
        
    }
</script>
<script type="text/javascript" src="/assets/member/js/sir.js"></script>
<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection