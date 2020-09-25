@extends("member.member_layout")
@section("member_content")
<div class="products">
	<div class="img-container">
		<div class="cert-member-name">{{ $customer->first_name }} {{ $customer->middle_name }} {{ $customer->last_name }}</div>
		<img src="/themes/{{ $shop_theme }}/img/certificate.jpg">
	</div>
</div>
@endsection
@section("member_script")
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/certificate.css">
@endsection