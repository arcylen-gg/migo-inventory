<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">{{$page}}</h4>
</div>
<div class="modal-body clearfix">
	<div class="slot-holder">
	@foreach($slots as $slot)
		<div class="slot-no-style"><div class="slotnum">{{$slot->slot_no}}</div class="slotnum"></div>
	@endforeach
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>