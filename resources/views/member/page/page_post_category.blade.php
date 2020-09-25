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
</style>
<link rel="stylesheet" type="text/css" href="/assets/custom_plugin/myDropList/css/myDropList.css">
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title item_title">Add New Post Category</h4>
</div>
<div class="modal-body modallarge-body-layout background-white">
	<form class="global-submit" role="form" action="/member/page/post/categorysubmit" method="post">
	    <input type="hidden" name="_token" value="{{ csrf_token() }}">
	   	<input type="hidden" name="post_image">
	        <div class="form-group">
	            <input type="text" name="post_category_name" class="form-control" placeholder="Enter title here">
	        </div>
	        <div class="form-group text-right">
	        	<button class="btn btn-primary" type="submit">Add</button>
	        </div>
	    </div>
	</form>
</div>