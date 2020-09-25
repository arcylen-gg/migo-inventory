<style type="text/css">

</style>
<link rel="stylesheet" type="text/css" href="/assets/custom_plugin/myDropList/css/myDropList.css">
	<form class="global-submit" role="form" action="/member/page/content/delete-maintenance" method="post">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="key" value="{{ Request::input('key') }}">
	<input type="hidden" name="id" value="{{ Request::input('id') }}">
	<div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h4 class="modal-title layout-modallarge-title item_title">Delete</h4>
	</div>
	<div class="modal-body modallarge-body-layout background-white">
		<div class="text-center">
			<h1>Are you sure to delete this data?</h1>
			<div style="margin-top: 15px;">
				<button class="btn btn-primary" type="submit">OK</button>
				<button class="btn btn-default" type="button" data-dismiss="modal">Cancel</button>
			</div>
		</div>	
	</div>
</form>