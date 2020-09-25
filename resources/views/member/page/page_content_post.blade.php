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
    <h4 class="modal-title layout-modallarge-title item_title">Manage Post</h4>
</div>
<div class="modal-body modallarge-body-layout background-white">
<form class="global-submit" role="form" action="/member/page/content/submit-post" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="limit" value="{{ $limit }}">
	<h2 style="margin-top: 0; margin-bottom: 25px;">Select {{ $limit == "all" ? "" : $limit }} Post</h2>
	@foreach($_post as $post)
	<div class="checkbox">
	  <label><input type="checkbox" name="post[]" value="{{ $post->post_id }}" {{ $post->selected ? "checked" : "" }}>{{ $post->post_title }}</label>
	</div>
	@endforeach
	<div class="form-group text-right">
    	<button class="btn btn-primary" type="submit">Submit</button>
    </div>
</div>
<script type="text/javascript">
function submit_done(data) 
{ 
	if (data.response_status == "warning") 
	{
		toastr.error(data.message);
	}
	else
	{
		toastr.success(data.message);
		$('.post-value[key="{{ Request::input("key") }}"]').val(data.post);
        $("#global_modal").modal('hide');
	}
}
</script>