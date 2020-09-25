<form class="global-submit form-horizontal" role="form" action="{link_submit_here}" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">{{ $membership->membership_name }}</h4>
	</div>
	<div class="modal-body clearfix">
		@if($color == "discount")
			@include("member.card.discount_card")
		@else
			@include("member.card.card")
		@endif
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button class="btn btn-primary btn-custom-primary" type="button">Submit</button>
	</div>
</form>