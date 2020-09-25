@extends("member.member_layout")
@section("member_content")
<div class="products">
	<div class="img-container">
		<img src="/themes/{{ $shop_theme }}/img/product.png">
	</div>
</div>
@endsection
@section("member_script")
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/mem_products.css">
@endsection