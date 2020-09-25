@extends("member.member_layout")
@section("member_content")
<div class="videos-wrapper">
	<div class="title"><span class="fa fa-video-camera">&nbsp;&nbsp;</span><span>Video Products</span></div>
	<!-- COLUMN TYPE -->
	
	{{-- <div class="row-no-padding clearfix">
		<div class="col-md-3">
			<a class="lsb-preview" href="">
				<div class="per-video-container">
					<div class="thumbnail">
						<img src="/themes/{{ $shop_theme }}/img/video-1.jpg">
					</div>
					<div class="video-title">
						<p>Introduction</p>
					</div>
				</div>
			</a>
		</div>
		<div class="col-md-3">
			<div class="per-video-container">
				<div class="thumbnail">
					<img src="/themes/{{ $shop_theme }}/img/video-2.jpg">
				</div>
				<div class="video-title">
					<p>Page Creation</p>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-video-container">
				<div class="thumbnail">
					<img src="/themes/{{ $shop_theme }}/img/video-3.jpg">
				</div>
				<div class="video-title">
					<p>Creation Of Page BOT Autoreply</p>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-video-container">
				<div class="thumbnail">
					<img src="/themes/{{ $shop_theme }}/img/video-4.jpg">
				</div>
				<div class="video-title">
					<p>Deletion Of Default Message</p>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-video-container">
				<div class="thumbnail">
					<img src="/themes/{{ $shop_theme }}/img/video-5.jpg">
				</div>
				<div class="video-title">
					<p>Creation Of Follow up Questions</p>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-video-container">
				<div class="thumbnail">
					<img src="/themes/{{ $shop_theme }}/img/video-6.jpg">
				</div>
				<div class="video-title">
					<p>Creation Of Facebook Marketing System</p>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-video-container">
				<div class="thumbnail">
					<img src="/themes/{{ $shop_theme }}/img/video-7.jpg">
				</div>
				<div class="video-title">
					<p>Posting With Image & Scheduling Using The System</p>
				</div>
			</div>
		</div>
	</div> --}}

	<!-- LIST TYPE -->
	@if(loop_content_condition($shop_theme_info,"videos","product_video_maintenance"))
		@foreach(unserialize(get_content($shop_theme_info,"videos", "product_video_maintenance")) as $video)
			<div class="per-video-container">
				<div class="row clearfix">
					<div class="col-md-12">
						<div class="col-md-4">
							<a href="{{ $video["video_link"] }}" data-lity>
								<div class="thumbnail">
									<img src="{{ $video["video_thumbnail_780x531"] }}">
								</div>
							</a>
						</div>
						<div class="col-md-8">
							<div class="video-title">
								<a href="{{ $video["video_link"] }}" data-lity><p>{{ $video["title"] }}</p></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach
	@endif

	<div class="per-video-container">
		<div class="row clearfix">
			<div class="col-md-12">
				<div class="col-md-4">
					<a href="https://player.vimeo.com/video/241715787" data-lity>
						<div class="thumbnail">
							<img src="/themes/{{ $shop_theme }}/img/video-1.jpg">
						</div>
					</a>
				</div>
				<div class="col-md-8">
					<div class="video-title">
						<a href="https://player.vimeo.com/video/241715787" data-lity><p>Introduction</p></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="per-video-container">
		<div class="row clearfix">
			<div class="col-md-12">
				<a href="https://player.vimeo.com/video/241712519" data-lity>
					<div class="col-md-4">
						<div class="thumbnail">
							<img src="/themes/{{ $shop_theme }}/img/video-2.jpg">
						</div>
					</div>
				</a>
				<div class="col-md-8">
					<div class="video-title">
						<a href="https://player.vimeo.com/video/241712519" data-lity><p>Page Creation</p></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="per-video-container">
		<div class="row clearfix">
			<div class="col-md-12">
				<div class="col-md-4">
					<a href="https://player.vimeo.com/video/241715846" data-lity>
						<div class="thumbnail">
							<img src="/themes/{{ $shop_theme }}/img/video-3.jpg">
						</div>
					</a>
				</div>
				<div class="col-md-8">
					<div class="video-title">
						<a href="https://player.vimeo.com/video/241715846" data-lity><p>Creation Of Page BOT Autoreply</p></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="per-video-container">
		<div class="row clearfix">
			<div class="col-md-12">
				<div class="col-md-4">
					<a href="https://player.vimeo.com/video/241716204" data-lity>
						<div class="thumbnail">
							<img src="/themes/{{ $shop_theme }}/img/video-4.jpg">
						</div>
					</a>
				</div>
				<div class="col-md-8">
					<div class="video-title">
						<a href="https://player.vimeo.com/video/241716204" data-lity><p>Deletion Of Default Message</p></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="per-video-container">
		<div class="row clearfix">
			<div class="col-md-12">
				<div class="col-md-4">
					<a href="https://player.vimeo.com/video/241716226" data-lity>
						<div class="thumbnail">
							<img src="/themes/{{ $shop_theme }}/img/video-5.jpg">
						</div>
					</a>
				</div>
				<div class="col-md-8">
					<div class="video-title">
						<a href="https://player.vimeo.com/video/241716226" data-lity><p>Creation Of Follow up Questions</p></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="per-video-container">
		<div class="row clearfix">
			<div class="col-md-12">
				<div class="col-md-4">
					<a href="https://player.vimeo.com/video/241716508" data-lity>
						<div class="thumbnail">
							<img src="/themes/{{ $shop_theme }}/img/video-6.jpg">
						</div>
					</a>
				</div>
				<div class="col-md-8">
					<div class="video-title">
						<a href="https://player.vimeo.com/video/241716508" data-lity><p>Creation Of Facebook Marketing System</p></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="per-video-container">
		<div class="row clearfix">
			<div class="col-md-12">
				<div class="col-md-4">
					<a href="https://player.vimeo.com/video/241716865" data-lity>
						<div class="thumbnail">
							<img src="/themes/{{ $shop_theme }}/img/video-7.jpg">
						</div>
					</a>
				</div>
				<div class="col-md-8">
					<div class="video-title">
						<a href="https://player.vimeo.com/video/241716865" data-lity><p>Posting With Image & Scheduling Using The System</p></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
@section("member_script")
<script type="text/javascript" src="/assets/front/js/global_checkout.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/profile.js"></script>
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/videos.css">
@endsection