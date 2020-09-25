@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
            <span class="page-title">Company <i class="fa fa-angle-double-right"></i> Company Information</span>
            <small>
            You can UPDATE or DELETE Company Information
            </small>
            </h1>
            <form action="/member/page/partner" method="post">
            {{csrf_field()}}
            <button type="submit" href="/member/page/partner" class="panel-buttons btn btn-custom-primary pull-right">ADD NEW COMPANY</button>
            </form>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray load-get">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div style="background-color: #fff; border-bottom: 2px solid #eee; padding: 5px 5px; width: 100%;">
                <div class="col-md-2 padding-lr-1">
                    <select class="form-control" id="locationDropdown">
                        <option value="">All Location</option>
                        @foreach($locationList as $locationListItem)
                            <option value="{{ $locationListItem->company_location }}">{{ $locationListItem->company_location }}</option>
                        @endforeach()
                    </select>
                </div>
            </div>
            <div class="text-center" id="spinningLoader" style="display:none;">
                <img src="/assets/images/loader.gif">
            </div>
            <div class="partner-result">
                <div class="table-responsive">
                    <table class="table table-condensed post-table">
                        <thead style="text-transform: uppercase">
                            <tr>
                                <th class="text-center">Company Logo</th>
                                <th class="text-center">Company Name</th>
                                <th class="text-center">Company Owner Name</th>
                                <th class="text-center">Contact Number</th>
                                <th class="text-center">Company Address</th>
                                <th class="text-center">Company Location</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($_company_info as $company_info)
                            <tr row="">
                                <td class="text-center">
                                    <img src="{{ $company_info->company_logo }}" class="img-thumbnail" alt="Cinque Terre" width="100" height="70">
                                </td>
                                <td class="text-center">{{ $company_info->company_name }}</td>
                                <td class="text-center">{{ $company_info->company_owner }}</td>
                                <td class="text-center">{{ $company_info->company_contactnumber }}</td>
                                <td class="text-center">{{ $company_info->company_address }}</td>
                                <td class="text-center">{{ $company_info->company_location }}</td>
                                <td class="text-center">
                                    <!-- ACTION BUTTON -->
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                                        <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-custom">
                                            <li ><a href="/member/page/partnerview/edit/{{ $company_info->company_id }}" ><i class="icon-fixed-width icon-pencil"></i>&nbsp;&nbsp;&nbsp;&nbsp;Edit</a></li>
                                            <li><a href="/member/page/partnerview/delete/{{ $company_info->company_id }}" ><i class="icon-fixed-width icon-trash"></i>&nbsp;&nbsp;Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
            </div>
        </div>
    </div>
</div>

{{-- @endif --}}
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/product.css">
@endsection
@section('script')
<script type="text/javascript">
function submit_done(data)
{
if (data.from == "category")
{
if (data.response_status == "warning")
{
toastr.error(data.message);
}
else
{
toastr.success(data.message);
console.log(data.category);
var append = '<div class="holder">'+
    '<input type="checkbox" name="post_category_id[]" value="'+data.category.post_category_id+'">'+
    '<span>'+data.category.post_category_name+'</span>'+
'</div>'
$('.category-check .check-container').append(append);
}
}
else
{
if (data.response_status == "warning")
{
toastr.error(data.message);
}
else
{
toastr.success(data.message);
$("#global_modal").modal('hide');
$(".load-get").load("/member/page/post .load-get .tab-content");
$(".load-get").addClass("panel panel-default panel-block panel-title-block panel-gray");
}
}
}
function submit_selected_image_done(data)
{
var image_path = data.image_data[0].image_path;
if (data.akey == 1)
{
$('input[name="post_image"]').val(image_path);
$('.image-put').attr("src", image_path);
}
else
{
tinyMCE.execCommand('mceInsertContent',false,'<img src="'+image_path+'"/>');
}
}
$(document).ready(function()
{
$("body").on("click", ".archive-post", function()
{
$.ajax({
url: '/member/page/post/archive/' + $(e.currentTarget).attr("delete"),
type: 'GET',
dataType: 'json',
})
.done(function(data)
{
toastr.success("Post has been successfully archived.");
$(".post-table tr[row='"+data.id+"']").remove();
})
.fail(function()
{
toastr.error("Some error occurred. Please try again.");
})
});
})
</script>
<script src="/assets/js/partner-filter-by-location.js"></script>
@endsection