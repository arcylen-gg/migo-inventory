
@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Collection &raquo; List </span>
                <small>
                    List of Collection
                </small>
            </h1>
            <a class="panel-buttons btn btn-custom-primary pull-right popup" size="lg" link="/member/ecommerce/product/collection" >Create Collection</a>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li id="all-list"  class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#all"><i class="fa fa-star"></i> Active Collection</a></li>
        <li id="archived-list" class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#archived"><i class="fa fa-trash"></i> Archived Collection</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search_name" placeholder="Search by CM Number" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    <div class="form-group tab-content panel-body collection-container">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>#</th>
                            <th>Collection Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(isset($_collection))
                        @foreach($_collection as $collection)
                            <tr>
                                <td>{{$collection->collection_id}}</td>
                                <td>{{$collection->collection_name}}</td>
                                <td>{{$collection->collection_description}}</td>
                                <td><input type="checkbox" name="collection_status" onclick="setActive({{$collection->collection_id}})" {{$collection->collection_status == 1 ? 'checked' : '' }} ></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                          <li><a class="popup" size="lg" link="/member/ecommerce/product/collection?id={{$collection->collection_id}}">Edit Collection</a></li>
                                          <li><a class="popup" size="md" link="/member/ecommerce/product/collection/archived/{{$collection->collection_id}}/archived">Archived</a></li>
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
         <div id="archived" class="tab-pane fade in">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>#</th>
                            <th>Collection Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(isset($_collection_archived))
                        @foreach($_collection_archived as $collection_archived)
                            <tr>
                                <td>{{$collection_archived->collection_id}}</td>
                                <td>{{$collection_archived->collection_name}}</td>
                                <td>{{$collection_archived->collection_description}}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                      </button>
                                      <ul class="dropdown-menu dropdown-menu-custom">
                                          <li><a class="popup" size="md" link="/member/ecommerce/product/collection/archived/{{$collection_archived->collection_id}}/restore">Restore</a></li>
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
@section('script')
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript">
    function submit_done(data)
{
    if(data.status == 'success')
    {       
        toastr.success("Success");
        $(".collection-container").load("/member/ecommerce/product/collection/list .collection-container");
        data.element.modal("hide");
        $("#all-list").addClass("active");
        $("#archived-list").removeClass("active");
    }
    else if(data.status == 'success-archived')
    {       
        toastr.success("Success");
        $(".collection-container").load("/member/ecommerce/product/collection/list .collection-container");
        data.element.modal("hide");
        $("#all-list").addClass("active");
        $("#archived-list").removeClass("active");
    }
    else if(data.status == "error")
    {
        toastr.warning(data.status_message);
        $(data.target).html(data.view);
    }
}
function setActive(id)
{
    $(".modal-loader").removeClass("hidden");
    $.ajax({
        url : "/member/ecommerce/product/collection/set_active",
        type : "get",
        data : {id : id},
        success : function(data)
        {
            toastr.success("Success");
            $(".collection-container").load("/member/ecommerce/product/collection/list .collection-container", function()
            {
                $(".modal-loader").addClass("hidden");
            });     
        }
    })
}
</script>
@endsection