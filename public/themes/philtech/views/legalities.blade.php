@extends("layout")
@section("content")
<div class="content mob-margin">
	<div class="legalities clearfix">
		<div class="container">
			<h2 class="legalities-title">Legalities</h2>
			<div class="legalities-container">
				<div class="row clearfix">
					@if(loop_content_condition($shop_theme_info, "legalities", "legalities_gallery"))
						@foreach(unserialize(get_content($shop_theme_info, "legalities", "legalities_gallery")) as $gallery)
							<div class="col-md-3 col-sm-6">
								<div class="legalities-holder" style="margin-bottom: 15px;">
									<div class="img">
										<a href="{{ $gallery }}" data-title="" data-lightbox="legalities-gallery">
											<img class="4-3-ratio" src="{{ $gallery }}">
										</a>
									</div>
								</div>
							</div>
						@endforeach
					@else
					<div class="col-md-12">
						<h4>No Data</h4>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section("css")
<!-- LIGHTBOX -->
{{-- <link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/assets/lightbox/css/lightbox.css"> --}}
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/legalities.css">
@endsection

@section("js")
{{-- <script type="text/javascript" src="/themes/{{ $shop_theme }}/assets/lightbox/js/lightbox.js"></script> --}}
<script type="text/javascript">
/*LIGHTBOX*/
lightbox.option({
  'disableScrolling': true,
  'wrapAround': true
});
</script>
@endsection