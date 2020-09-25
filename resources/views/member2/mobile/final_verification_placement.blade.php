<div data-page="report" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="index.html" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Placement</div>
            <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
        </div>
    </div>
    <div class="page-content">
        <div class="popup-proceed1">
            <div class="modal-content">
                <div class="load-final-verification">
                    <form class="final-verification-placement-submit" method="post">
                    	<div class="modal-header">
                    		<button type="button" class="close" data-dismiss="modal">×</button>
                    		<h4 class="modal-title">Verify Placement Details</h4>
                    	</div>
                    	<input type="hidden" class="final_verification_slot_id" name="slot_id" value="{{$target_slot->slot_id}}">
                    	<input type="hidden" class="final_verification_slot_placement" name="slot_placement" value="{{$placement->slot_id}}">
                    	<input type="hidden" class="final_verification_slot_position" name="slot_position" value="{{$position}}">
                    	<div class="modal-body clearfix">
                    		<div class="warning"><b><i class="fa fa-warning"></i> WARNING!</b> Once you process this slot, you will not be able to undo your actions. This slot will be placed using the information below.</div>
                    		<div class="row">
                    			<div class="col-md-6">
                    				<div class="value-set">
                    					<div class="labels">SLOT</div>
                    					<div class="values">SLOT NO. {{ $target_slot->slot_no }}</div>
                    				</div>
                    				<div class="value-set">
                    					<div class="labels">PLACEMENT NAME</div>
                    					<div class="values">{{ $placement->first_name }} {{ $placement->last_name }}</div>
                    				</div>
                    			</div>  
                    			<div class="col-md-6">
                    				<div class="value-set">
                    					<div class="labels">SLOT PLACEMENT</div>
                    					<div class="values">SLOT NO. {{ $placement->slot_no }}</div>
                    				</div>
                    				<div class="value-set">
                    					<div class="labels">POSITION</div>
                    					<div class="values">{{ $position }}</div>
                    				</div>
                    			</div>
                    		</div>
                    	</div>
                    	<div class="modal-footer text-center">
                    		<button class="btn btn-primary btn-custom-primary process-slot-placement" type="button"><i class="fa fa-check"></i> Confirm Placement</button>
                    	</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>