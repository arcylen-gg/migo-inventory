@extends('mlm.layout')
@section('content')
<?php 
$data['title'] = 'Binary Genealogy';
$data['sub'] = '<div class="col-md-12">
	<div class="col-md-1"><span style="color: gray" class="pull-right">Legend:</span></div>
	<button class="btn btn-primary col-md-3" style="backgroung-color: #519FCD !important" readonly>Occupied Slot</button>
	<button class="btn btn-success col-md-3" style="background-color: #86CC51 !important" readonly>Unoccupied Slot</button>
</div>';
$data['icon'] = 'fa fa-link';
?>
@include('mlm.header.index', $data)
<style type="text/css">

</style>

<div id="loadingMessage" class="col-md-12"><center>Loading ...</center></div>
<iframe src="" frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%" id="foo"></iframe>

@endsection
@section('js')
<script type="text/javascript">
// $('#foo').attr('width', screen.width);
$('#foo').attr('height', screen.height);
// $('#foo').css('width', screen.width);
$('#foo').css('height', screen.height);
$(document).ready(function() {
	var src1 = '/mlm/slot/genealogy?id={{$slot_i}}&mode=binary';
	$("#foo").attr("src", src1);
 });
$('#foo').ready(function () {
    $('#loadingMessage').css('display', 'block');
});
$('#foo').load(function () {
    $('#loadingMessage').css('display', 'none');
});</script>
@endsection