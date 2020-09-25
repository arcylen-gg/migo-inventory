@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-vcard"></i>
            <h1>
                <span class="page-title">Position</span>
                <small>
                    List of Position.
                </small>
            </h1>
            <div class="text-right">
            <a class="btn btn-primary panel-buttons popup" link="/member/pis/agent/position/add" size="md" data-toggle="modal" data-target="#global_modal">Add Postion</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" href="#all"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active Position</a></li>
                  <li id="archived-list"><a data-toggle="tab" href="#archived"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Archived Position</a></li>
                </ul>
            </div>
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="search" name="" class="form-control srch-warehouse-txt" placeholder="Start typing position name">
                </div>
            </div>
        </div>

        <div class="form-group tab-content panel-body position-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Position ID</th>
                                <th>Position Name</th>
                                <th>Position Code</th>
                                <th>Position Date Created</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_position != null)
                            @foreach($_position as $position)
                            <tr>
                                <td>{{$position->position_id}}</td>
                                <td>{{$position->position_name}}</td>
                                <td >{{$position->position_code}}</td>
                                <td>{{date('F d, Y', strtotime($position->position_created))}}</td>
                                <td class="text-center">
                                    <a link="/member/pis/agent/position/edit/{{$position->position_id}}" size="md" class="popup">Edit</a> |
                                    <a link="/member/pis/agent/position/archived/{{$position->position_id}}/archived" size="md" class="popup">Archived</a> 
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="archived" class="tab-pane fade in">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">                    
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Position ID</th>
                                <th>Position Name</th>
                                <th>Position Code</th>
                                <th>Position Date Created</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_position_archived != null)
                            @foreach($_position_archived as $position_archived)
                            <tr>
                                <td>{{$position->position_id}}</td>
                                <td>{{$position_archived->position_name}}</td>
                                <td >{{$position_archived->position_code}}</td>
                                <td>{{date('F d, Y', strtotime($position->position_created))}}</td>
                                <td class="text-center">
                                    <a link="/member/pis/agent/position/archived/{{$position_archived->position_id}}/restore" size="md" class="popup">Restore</a> 
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
@endsection
@section("script")
<script type="text/javascript" src="/assets/member/js/position.js"></script>
<script type="text/javascript">
    if(data.type == "position")
    {
        toastr.success("Success");
        $(".drop-down-position").load("/member/pis/agent/position/add .drop-down-position option", function()
        {                
             $(".drop-down-position").globalDropList("reload"); 
             $(".drop-down-position").val(data.id).change();              
        });
        
        data.element.modal("hide");
        // $('.multiple_global_modal').modal('hide');
    }
    else if(data.status == "error")
    {
        toastr.warning(data.status_message);
        $(data.target).html(data.view);
    }
    else
    {
        $(data.error).each(function( index )
        {
          toastr.warning(data.error[index]);
        });
    }
</script>
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->
@endsection