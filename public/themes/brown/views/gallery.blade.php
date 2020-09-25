@extends("layout")
@section("content")
<div class="content">
	<div class="top-1-container">
		<div class="container">
			<div class="title-container">
				Gallery
			</div>
		</div>
	</div>
	<div class="content-container">
		<div class="container">
			<div class="title-container">Albums
				<div class="line-bot">
				</div>
			</div>
			<div class="albums-content row clearfix">
				@if(is_serialized(get_content($shop_theme_info, "gallery", "gallery_maintenance")))
					@foreach(unserialize(get_content($shop_theme_info, "gallery", "gallery_maintenance")) as $key => $gallery)
					<div class="col-md-4">
						<a href="/gallery_content/{{ $key }}" style="text-decoration: none;">
							<div class="per-album-container">
								<div class="img-container"><img src="{{ isJson($gallery["image"]) ? json_decode($gallery["image"])[0]->image_path : '/assets/mlm/img/placeholder.jpg' }}"></div>
								<div class="album-title-container">{{ $gallery["title"] }}</div>
								<div class="album-date-container">{{ date("F d, Y", strtotime($gallery["posted"])) }}</div>
							</div>
						</a>
					</div>
					@endforeach
				@endif
			</div>
		</div>
	</div>
	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/gallery.css">
@endsection

@section("js")
<script type="text/javascript">
$(document).ready(function()
{
	/*scroll up*/
	$(window).scroll(function () {
        if ($(this).scrollTop() > 700) {
            $('.scroll-up').fadeIn();
        } else {
            $('.scroll-up').fadeOut();
        }
    });

    $('.scroll-up').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 700);
        return false;
    });
});
</script>
@endsection