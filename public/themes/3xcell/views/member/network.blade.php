@extends("member.member_layout")
@section("member_content")
	@include('member2.include_member_network')
@endsection
@section("member_script")

<script type="text/javascript">
	$(".select-slot").change(function(e)
	{
		window.location.href='/members/network?slot_no=' + $(e.currentTarget).val();
	})
</script>

@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/report.css">
@endsection