@extends("member.member_layout")
@section("member_content")
<div class="ebooks-wrapper">
	<div class="title"><span class="fa fa-book">&nbsp;&nbsp;</span><span>Ebooks</span></div>
	<div class="row clearfix">

		<div class="col-md-3">
			<div class="per-item">
				<a href="/themes/{{ $shop_theme }}/assets/pdf/1.pdf"><div class="thumbnail"><img src="/themes/{{ $shop_theme }}/img/ebook-1.jpg"></div></a>
				<!-- <div class="title">27 Essential Rules Of Internet Marketing.pdf</div> -->
				<object data="1.pdf" type="application/pdf">
				  <a href="/themes/{{ $shop_theme }}/assets/pdf/1.pdf"><p>27 Essential Rules Of Internet Marketing</p></a>
				</object>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-item">
				<a href="/themes/{{ $shop_theme }}/assets/pdf/2.pdf"><div class="thumbnail"><img src="/themes/{{ $shop_theme }}/img/ebook-2.jpg"></div></a>
				<!-- <div class="title">27 Essential Rules Of Internet Marketing.pdf</div> -->
				<object data="2.pdf" type="application/pdf">
				  <a href="/themes/{{ $shop_theme }}/assets/pdf/2.pdf"><p>Attract Anything You Want In Life</p></a>
				</object>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-item">
				<a href="/themes/{{ $shop_theme }}/assets/pdf/3.pdf"><div class="thumbnail"><img src="/themes/{{ $shop_theme }}/img/ebook-3.jpg"></div></a>
				<!-- <div class="title">27 Essential Rules Of Internet Marketing.pdf</div> -->
				<object data="3.pdf" type="application/pdf">
				  <a href="/themes/{{ $shop_theme }}/assets/pdf/3.pdf"><p>Facebook Marketing Extreme</p></a>
				</object>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-item">
				<a href="/themes/{{ $shop_theme }}/assets/pdf/4.pdf"><div class="thumbnail"><img src="/themes/{{ $shop_theme }}/img/ebook-4.jpg"></div></a>
				<!-- <div class="title">27 Essential Rules Of Internet Marketing.pdf</div> -->
				<object data="4.pdf" type="application/pdf">
				  <a href="/themes/{{ $shop_theme }}/assets/pdf/4.pdf"><p>Google Tools To Help Marketers Succeed</p></a>
				</object>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-item">
				<a href="/themes/{{ $shop_theme }}/assets/pdf/5.pdf"><div class="thumbnail"><img src="/themes/{{ $shop_theme }}/img/ebook-5.jpg"></div></a>
				<!-- <div class="title">27 Essential Rules Of Internet Marketing.pdf</div> -->
				<object data="5.pdf" type="application/pdf">
				  <a href="/themes/{{ $shop_theme }}/assets/pdf/5.pdf"><p>Grow Rich While You Sleep</p></a>
				</object>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-item">
				<a href="/themes/{{ $shop_theme }}/assets/pdf/6.pdf"><div class="thumbnail"><img src="/themes/{{ $shop_theme }}/img/ebook-6.jpg"></div></a>
				<!-- <div class="title">27 Essential Rules Of Internet Marketing.pdf</div> -->
				<object data="6.pdf" type="application/pdf">
				  <a href="/themes/{{ $shop_theme }}/assets/pdf/6.pdf"><p>Midas Touch</p></a>
				</object>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-item">
				<a href="/themes/{{ $shop_theme }}/assets/pdf/7.pdf"><div class="thumbnail"><img src="/themes/{{ $shop_theme }}/img/ebook-7.jpg"></div></a>
				<!-- <div class="title">27 Essential Rules Of Internet Marketing.pdf</div> -->
				<object data="7.pdf" type="application/pdf">
				  <a href="/themes/{{ $shop_theme }}/assets/pdf/7.pdf"><p>Millionaire Mindset</p></a>
				</object>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-item">
				<a href="/themes/{{ $shop_theme }}/assets/pdf/8.pdf"><div class="thumbnail"><img src="/themes/{{ $shop_theme }}/img/ebook-8.jpg"></div></a>
				<!-- <div class="title">27 Essential Rules Of Internet Marketing.pdf</div> -->
				<object data="8.pdf" type="application/pdf">
				  <a href="/themes/{{ $shop_theme }}/assets/pdf/8.pdf"><p>Positive Attitude For Unlimited Success</p></a>
				</object>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-item">
				<a href="/themes/{{ $shop_theme }}/assets/pdf/9.pdf"><div class="thumbnail"><img src="/themes/{{ $shop_theme }}/img/ebook-9.jpg"></div></a>
				<!-- <div class="title">27 Essential Rules Of Internet Marketing.pdf</div> -->
				<object data="9.pdf" type="application/pdf">
				  <a href="/themes/{{ $shop_theme }}/assets/pdf/9.pdf"><p>Rich Dad Poor Dad</p></a>
				</object>
			</div>
		</div>
		<div class="col-md-3">
			<div class="per-item">
				<a href="/themes/{{ $shop_theme }}/assets/pdf/10.pdf"><div class="thumbnail"><img src="/themes/{{ $shop_theme }}/img/ebook-10.jpg"></div></a>
				<!-- <div class="title">27 Essential Rules Of Internet Marketing.pdf</div> -->
				<object data="10.pdf" type="application/pdf">
				  <a href="/themes/{{ $shop_theme }}/assets/pdf/10.pdf"><p>Rich Kid Smart Kid</p></a>
				</object>
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/ebooks.css">
@endsection