@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Page <i class="fa fa-angle-double-right"></i> Posts</span>
                <small>
                    Add a post for your website.
                </small>
            </h1>
            <button type="button" class="panel-buttons btn btn-custom-primary pull-right popup" link="/member/page/post/add" size="lg" data-toggle="modal" data-target="#global_modal">Add Post</button>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
{{-- @if(count($_post) == 0)
<div class="load-get">
    <div class="row">
        <div class="col-md-12 text-center">
            <div class="trial-warning clearfix">
                <div class="no-product-title">Add your first post</div>
                <div class="no-product-subtitle">You’re just a few steps away from adding your first post.</div>
            </div>
        </div>
    </div>
</div>
@else --}}
<div class="panel panel-default panel-block panel-title-block panel-gray load-get">
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div style="background-color: #fff; border-bottom: 2px solid #eee; padding: 5px 5px; width: 100%;">
                <a style="display: inline-block; padding: 7.5px 15px; {{ !Request::input("archive") ? "color: #fff; background-color: #1682ba;" : "color: #333;" }}" href="/member/page/post">All</a>
                <a style="display: inline-block; padding: 7.5px 15px; {{ Request::input("archive") ? "color: #fff; background-color: #1682ba;" : "color: #333;" }}" href="/member/page/post?archive=1">Archived</a>
            </div>
            <div class="table-responsive">
                <table class="table table-condensed post-table">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th class="text-center">Title</th>
                            <th class="text-center">Author</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">DATE</th>
                            <th class="text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_post as $post)
                        <tr row="{{ $post->main_id }}">
                            <td class="text-center">{{ $post->post_title }}</td>
                            <td class="text-center">{{ $post->user_first_name . " " . $post->user_last_name }}</td>
                            <td class="text-center">
                            @if(count($post->categories) == 0)
                                Uncategorized
                            @else
                                @foreach($post->categories as $category)
                                    @if(end($post->categories) == $category)
                                    {{ $category->post_category_name }}
                                    @else
                                    {{ $category->post_category_name }},
                                    @endif
                                @endforeach
                            @endif
                            </td>
                            <td class="text-center">{{ date("F d, Y", strtotime($post->post_date)) }}</td>
                            <td class="text-center">
                                <!-- ACTION BUTTON -->
                                <div class="btn-group">
                                  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu">
                                    <li><a class="popup" link="/member/page/post/edit/{{ $post->main_id }}" size="lg">Edit</a></li>
                                    <li><a class="archive-post" href="/member/page/post/{{ Request::input("archive") ? "unarchive" : "archive" }}/{{ $post->main_id }}" style="cursor: pointer;">{{ Request::input("archive") ? "Unarchive" : "Archive" }}</a></li>
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
@endsection