@extends("layout")
@section("content")
<!-- CONTENT -->
	<div class="container content">
		{!! get_content($shop_theme_info, "terms_and_conditions", "refund_and_return_policy") !!}
	</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/return_policy.css">
@endsection
