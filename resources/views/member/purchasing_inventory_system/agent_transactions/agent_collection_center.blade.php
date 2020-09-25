@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Agent Collection Center </span>
                <small>
                    Agent Collection List
                </small>
            </h1>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer all-sir"><a class="cursor-pointer" onclick="select('all')" data-toggle="tab" ><i class="fa fa-star"></i> All</a></li>
        <li class="cursor-pointer sir-class"><a class="cursor-pointer"  onclick="select(1)" data-toggle="tab" ><i class="fa fa-refresh"></i> Open SIR</a></li>
        <li class="cursor-pointer sir-class"><a class="cursor-pointer"  onclick="select(2)" data-toggle="tab" ><i class="fa fa-window-close"></i> Closed SIR</a></li>
    </ul>

    <div class="form-group tab-content panel-body collection-container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>SIR No</th>
                            <th>Agent Name</th>
                            <th>Total Collectibles</th>
                            <th>Total Collection</th>
                            <th>Status</th>
                            <th>Loss/Over</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($_sir)
                        @foreach($_sir as $sir)
                            <tr>
                                <td>{{sprintf("%'.04d\n", $sir->sir_id)}}</td>
                                <td>{{$sir->first_name." ".$sir->middle_name." ".$sir->last_name}}</td>
                                <td>{{$sir->total_collectibles}}</td>
                                <td>{{$sir->total_collection}}</td>
                                <td>
                                    <span style="color: {{$sir->loss_over < 0 ? 'red': 'green'}}">
                                        @if($sir->agent_collection_remarks == "")
                                            Not Updated
                                        @else
                                            @if($sir->loss_over < 0)
                                               Loss
                                            @elseif($sir->loss_over == 0)
                                               Complete
                                            @else
                                               Over
                                            @endif
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span style="color: {{$sir->loss_over < 0 ? 'red': 'green'}}">
                                        {{currency("Php" , $sir->loss_over)}}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($sir->ilr_status == 2)
                                    <a class="btn btn-default form-control">CLOSED</a>
                                    @else
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                        <li><a link="/member/pis_agent/collection_update/{{$sir->sir_id}}" size="md" class="popup">Update Collection</a></li>
                                      </ul>
                                    </div>
                                    @endif
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
@section("script")

<script type="text/javascript">   
function submit_done(data)
{
    if(data.status == "success")
    {
        toastr.success("Success");
        $('#global_modal').modal('toggle');
        $(".collection-container").load("/member/cashier/collection .collection-container");
        data.element.modal("hide");
    }
    else if(data.status == "error")
    {
        toastr.warning(data.status_message);
        $(data.target).html(data.view);
    }
}
function select(status)
{
    $(".collection-container").load("/member/pis_agent/collection?status="+status+ " .collection-container");
}
</script>
@endsection