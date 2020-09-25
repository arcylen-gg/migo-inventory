@extends("member.member_layout")
@section("member_content")
	<div class="text-center">DASHBOARD OF SIR ARNOLD IS BEING RE-DEVELOPED</div>
@endsection

@section("member_script")
<script type="text/javascript" src="assets/member/js/non_member.js"></script>
<script type="text/javascript" src='/assets/chartjs/Chart.bundle.min.js'></script>
@endsection

@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/member_dashboard.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/nonmember_dashboard.css">
@endsection