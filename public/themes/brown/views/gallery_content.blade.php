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
			<div class="title-container">
				<div class="album-title-container">{{ unserialize(get_content($shop_theme_info, "gallery", "gallery_maintenance"))[$id]["title"] }}</div>
				<div class="album-date">{{ date("F d, Y", strtotime(unserialize(get_content($shop_theme_info, "gallery", "gallery_maintenance"))[$id]["posted"])) }}</div>
				<div class="line-bot">
				</div>
			</div>
			<div class="albums-content row clearfix">
				<!-- PER PICTURES -->
				@if(isJson(unserialize(get_content($shop_theme_info, "gallery", "gallery_maintenance"))[$id]["image"]))
					@foreach(json_decode(unserialize(get_content($shop_theme_info, "gallery", "gallery_maintenance"))[$id]["image"]) as $key => $gallery)
					<div class="col-md-3">
						<div class="per-album-container">
							<div class="img-container">
								<a href="{{ $gallery->image_path }}" data-title="" data-lightbox="company-gallery">
									<img src="{{ $gallery->image_path }}">
								</a>
							</div>
						</div>
					</div>
					@endforeach
				@endif
				{{-- <div class="col-md-3">
					<div class="per-album-container">
						<div class="img-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample2.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample2.png">
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="per-album-container">
						<div class="img-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample2.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample2.png">
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="per-album-container">
						<div class="img-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample2.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample2.png">
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="per-album-container">
						<div class="img-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample2.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample2.png">
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="per-album-container">
						<div class="img-container">
							<a href="/themes/{{ $shop_theme }}/img/gallery-sample2.png" data-title="" data-lightbox="company-gallery">
								<img src="/themes/{{ $shop_theme }}/img/gallery-sample2.png">
							</a>
						</div>
					</div>
				</div> --}}
			</div>
		</div>
	</div>
	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/gallery_content.css">
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

    /*LIGHTBOX*/
    lightbox.option({
      'disableScrolling': true,
      'wrapAround': true
    });


});
</script>
@endsection