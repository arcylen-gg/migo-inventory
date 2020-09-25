<form class="final-verification-submit" method="post">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">FINAL VERIFICATION</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="warning"><b><i class="fa fa-warning"></i> WARNING!</b> Once you process this transaction, you will not be able to undo your actions. Your slot will be created using the information below.</div>
		<div class="row">
			<div class="col-md-6">
				<div class="value-set">
					<div class="labels">SPONSOR SLOT</div>
					<div class="values">SLOT NO. {{ $sponsor->slot_no }}</div>
				</div>
				<div class="value-set">
					<div class="labels">PIN</div>
					<div class="values">{{ $pin }}</div>
				</div>
			</div>
			<div class="col-md-6">

				<div class="value-set">
					<div class="labels">SPONSOR NAME</div>
					<div class="values">{{ $sponsor_customer->first_name }} {{ $sponsor_customer->last_name }}</div>
				</div>
				<div class="value-set">
					<div class="labels">ACTIVATION</div>
					<div class="values">{{ $activation }}</div>
				</div>
			</div>

		</div>
	</div>
	<div class="modal-footer text-center">
		<button type="button" class="btn btn-custom-def" data-dismiss="modal">Cancel</button>
		<button class="btn btn-jca-custom-default process-slot-creation" type="button"><i class="fa fa-check"></i> Process Using Details Above</button>
	</div>
</form>