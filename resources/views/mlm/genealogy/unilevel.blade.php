@extends('mlm.layout')
@section('content')
<?php 
$data['title'] = 'Unilevel Genealogy';
$data['sub'] = '';
$data['icon'] = 'fa fa-link';
?>
@include('mlm.header.index', $data)
<style type="text/css">

</style>
<div class="col-md-12 hide">
	@if($head == 'true')
	<button class="btn btn-primary pull-right" onClick="view_original()">View Your Genealogy</button>
	@else
		@if($slot_now != null)
			<button class="btn btn-primary pull-right" onClick="view_full()">View Full Genealogy</button>
		@endif
	@endif
</div>

@if($slot_now != null)
<div id="loadingMessage" class="col-md-12"><center>Loading ...</center></div>	
<iframe src="" frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%" id="foo"></iframe>
@else
<div class="container text-center">
    <div class="row vcenter" style="margin-top: 20%;">
      <div class="col-md-12">
        <div class="error-template">
          <h1 class="oops">Oops!</h1>
          <h2 class="message">403 Permission Denied</h2>
          <div class="error-details">
            Sorry, you do not have access to this page, please contact your administrator. You must have a slot to view this page.
          </div>
        </div>
      </div>
    </div>
@endif

@endsection
@section('js')
<script type="text/javascript">
$(document).ready(function() {

	$('#foo').attr('width', screen.width);
	$('#foo').attr('height', screen.height);
	$('#foo').css('width', screen.width);
	$('#foo').css('height', screen.height);
	@if($head == 'true')
		var src1 = '/mlm/slot/genealogy?id=1&mode=sponsor';
	@else
		@if($slot_now != null)
		var src1 = '/mlm/slot/genealogy?id={{$slot_now->slot_id}}&mode=sponsor&width=' + screen.width +'&height=' + screen.height;
		@else
		// var src1 = '/mlm/slot/genealogy?id=1&mode=sponsor';
		@endif
	@endif
	
	// console.log(src1);
	$("#foo").attr("src", src1);
 });
$('#foo').ready(function () {
    $('#loadingMessage').css('display', 'block');
});
$('#foo').load(function () {
    $('#loadingMessage').css('display', 'none');
});
function view_full()
{
	location.href = 'mlm/genealogy/unilevel?head=true';
}
function view_original()
{
	location.href = 'mlm/genealogy/unilevel';
}
</script>
@endsection