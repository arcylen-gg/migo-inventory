<form class="global-submit" action="/member/page/themes/activate_submit">
	{!! csrf_field() !!}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Activate Themes</h4>
	</div>
	
	<div class="modal-body clearfix">
		<div class="modal-create" style="display: block;">
			<input type="hidden" name="theme" value="{{ $key }}">
		    <div class="form-horizontal">
		        <div class="form-group">
		            <label for="" class="col-md-4">Theme Name</label>
		            <div class="col-md-8">
		                <input type="text" class="form-control" disabled="disabled" id="shipping_name" value="{{ $theme['theme_name'] }}" name="">
		            </div>
		        </div>
		        <div class="form-group">
		            <label for="" class="col-md-4">Color Scheme</label>
		            <div class="col-md-8">
		                <select name="shop_theme_color" class="form-control theme-select">
		                	@foreach($theme["colors"] as $key_color => $color)
		                	<option path="/public/themes/{{ $key }}/css_image/{{ $key_color }}.jpg" value="{{ $key_color }}">{{ $key_color }}</option>
		                	@endforeach
		                </select>
		            </div>
		        </div>
		        <div class="form-group text-center" style="margin-top: 30px; height: 230px;">
		        	<img class="theme-image" src="">
		        </div>
		    </div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
		<!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
		<button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Activate Theme</button>
	</div>
</form>

<script type="text/javascript">
	function submit_done(url)
	{
		$('#global_modal').modal('toggle');
		window.location.reload();
	}

	$(".theme-select").unbind("change");


	update_selected_theme_image();
	$(".theme-select").bind("change", function(e)
	{
		update_selected_theme_image();
	})
	function update_selected_theme_image()
	{
		$selected_path = $(".theme-select option:selected").attr("path");
		$(".theme-image").attr("src", $selected_path);
	}
</script>