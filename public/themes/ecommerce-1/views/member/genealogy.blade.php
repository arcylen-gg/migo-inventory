@extends("member.member_layout")
@section("member_content")
<div class="genealogy-container">
	<div class="genealogy-header clearfix">
		<div class="left">
			<input type="hidden" class="mode-genealogy" name="" value="{{$mode or ''}}">
			
			<div class="icon">
				<div class="brown-icon-genealogy" style="font-size: 40px;"></div>
			</div>
			<div class="text">
				<div class="name">Genealogy</div>
			</div>
		</div>
		<div class="right">
			<div class="legend">
				<div class="legend-label">Legend:</div>
				<div class="legend-select">
					<select class="form-control select-slot">
						@foreach($_slot as $slot)
							<option value="{{$slot->slot_no}}">SLOT# {{$slot->slot_no}}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="loading-content text-center hidden">
		<i class="fa fa-spinner fa-spin fa-2x"></i>
	</div>
	<div class="genealogy-content">
		<iframe  class="genealogy-frame" frameborder="0"  width="100%" height="550px" src="/members/genealogy-tree?slot_no={{$slot_no or ''}}&mode={{$mode or ''}}"></iframe>
	</div>
</div>
@endsection
@section("member_script")
<script type="text/javascript">
	$(".select-slot").change(function()
	{
		$('.loading-content').removeClass('hidden');
		$('.genealogy-frame').attr('src','/members/genealogy-tree?slot_no='+$(this).val()+'&mode='+$('.mode-genealogy').val());
		$('.genealogy-frame').css('opacity', 0);
		setInterval(function()
		{
			$('.loading-content').addClass('hidden');
			$('.genealogy-frame').css('opacity', 1);
		},1000);
	});	
</script>
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/genealogy.css">
@endsection