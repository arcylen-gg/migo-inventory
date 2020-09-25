@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-wrench"></i>
            <h1>
                <span class="page-title">Manufacturer</span>
                <small>
                    List of manufacturer.
                </small>
            </h1>
            <div class="text-right">
            <a class="btn btn-primary panel-buttons popup" link="/member/item/manufacturer/add" size="md" data-toggle="modal" data-target="#global_modal">Add Manufacturer</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" href="#all"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active Manufacturer</a></li>
                  <li id="archived-list"><a data-toggle="tab" href="#archived"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Archived Manufacturer</a></li>
                </ul>
            </div>
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="search" name="" class="form-control srch-warehouse-txt" placeholder="Start typing warehouse">
                </div>
            </div>
        </div>

        <div class="form-group tab-content panel-body manufacturer-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact Information</th>
                                <th>Address</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_manufacturer != null)
                            @foreach($_manufacturer as $manufacturer)
                            <tr>
                                <td>{{$manufacturer->manufacturer_name}}</td>
                                <td>
                                    <div>{{$manufacturer->manufacturer_fname." ".$manufacturer->manufacturer_mname." ".$manufacturer->manufacturer_lname}}</div>
                                    <div>{{$manufacturer->phone_number}}</div>
                                    <div>{{$manufacturer->email_address}}</div>
                                    <div><a href="{{$manufacturer->website}}">{{$manufacturer->website}}</a></div>
                                </td>
                                <td>
                                    {{$manufacturer->manufacturer_address}}
                                </td>
                                <td class="text-center">
                                    <a link="/member/item/manufacturer/add?id={{$manufacturer->manufacturer_id}}" size="md" class="popup">Edit</a> |
                                    <a link="/member/item/manufacturer/archived/{{$manufacturer->manufacturer_id}}/archived" size="md" class="popup">Archived</a> 
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
                                <th>Name</th>
                                <th>Contact Information</th>
                                <th>Address</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_manufacturer_archived != null)
                            @foreach($_manufacturer_archived as $manufacturer_archived)
                            <tr>
                                <td>{{$manufacturer_archived->manufacturer_name}}</td>
                                <td>
                                    <div>{{$manufacturer_archived->phone_number}}</div>
                                    <div>{{$manufacturer_archived->email_address}}</div>
                                    <div><a href="{{$manufacturer_archived->website}}">{{$manufacturer_archived->website}}</a></div>
                                </td>
                                <td>
                                    {{$manufacturer_archived->manufacturer_address}}
                                </td>
                                <td class="text-center">
                                    <a link="/member/item/manufacturer/archived/{{$manufacturer_archived->manufacturer_id}}/restore" size="md" class="popup">Restore</a> 
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
<script type="text/javascript">
function submit_done(data)
{
    if(data.status == "success")
    {
        toastr.success("Success");
        $(".manufacturer-container").load("/member/item/manufacturer .manufacturer-container"); 
        $(data.target).html(data.view);
        $('#global_modal').modal('toggle');
        $("#all-list").addClass("active");
        $("#archived-list").removeClass("active");
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
}
</script>
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->
@endsection