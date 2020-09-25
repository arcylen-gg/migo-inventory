<style type="text/css">
.category-check
{
    border: 1px solid #ddd;
    padding: 7.5px 15px;
}
.category-check .holder
{
	margin-bottom: 2.5px;
}
.category-check .holder input, .category-check .holder span
{
	vertical-align: middle;
	display: inline-block;
	margin: 0;
}
.category-check .holder input
{
	margin-right: 5px;
}
.image-gallery
{
	cursor: pointer;
	object-fit: cover;
}
</style>
<link rel="stylesheet" type="text/css" href="/assets/custom_plugin/myDropList/css/myDropList.css">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Edit Post # {{ $id }}</h4>
</div>
<div class="modal-body modallarge-body-layout background-white">
	<form class="global-submit" role="form" action="/member/page/post/editsubmit/{{ $id }}" method="post">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}">
	   	<input type="hidden" name="post_image" value="{{ $data->post_image }}">
	        <div class="form-group">
	            <input type="text" value="{{ $data->post_title }}" name="post_title" class="form-control" placeholder="Enter title here">
	        </div>
	        <div class="form-group">
	        	<label>Content</label>
	        	<div><button class="btn btn-default btn-sm image-gallery" key="2" type="button" style="margin-bottom: 7.5px;">Add Media</button></div>
	        	<textarea class="form-control mce" name="post_content">{{ $data->post_content }}</textarea>
	        </div>
	        <div class="form-group">
	        	<div class="row clearfix">
	        		<div class="col-md-6">
	        			<label style="opacity: 0">Excerpt</label>
	        			<img class="img-responsive image-put image-gallery image-gallery-single match-height" key="1" src="{{ $data->post_image }}" style="width: 100%; border: 1px solid #ddd;">
	        		</div>
	        		<div class="col-md-6">
	        			<label>Excerpt</label>
	        			<textarea class="form-control match-height" name="post_excerpt">{{ $data->post_excerpt }}</textarea>
	        		</div>
	        	</div>
	        </div>
	        <div class="form-group">
	        	<div class="row clearfix">
	        		<div class="col-md-6">
	        			<label>Type</label>
			        	<select class="form-control" name="post_type">
			        		<option value="post" {{ $data->post_type == "post" ? "selected" : "" }}>Post</option>
			        	</select>
	        		</div>
	        		<div class="col-md-6">
	        			<label>Categories</label>
	        			<div class="category-check">
	        				<div class="check-container">
	        					@foreach($_category as $category)
		        				<div class="holder">
		        					<input type="checkbox" name="post_category_id[]" value="{{ $category->post_category_id }}" {{ $category->checked ? "checked" : "" }}>
		        					<span>{{ $category->post_category_name }}</span>
		        				</div>
		        				@endforeach
	        				</div>
	        				<button type="button" class="btn btn-primary btn-sm popup" link="/member/page/post/category" size="lg"> Add Category</button>
	        			</div>
	        		</div>
	        	</div>
	        </div>
	        <div class="form-group text-right">
	        	<button class="btn btn-primary" type="submit">Publish</button>
	        </div>
	    </div>
	</form>
</div>
<script type="text/javascript" src="/assets/member/js/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({ 
	selector:'.mce',
	plugins: "autoresize",
 });

$(document).ready(function() 
{
	setTimeout(function() {
		$(".match-height").matchHeight();
	}, 100);
});
</script>