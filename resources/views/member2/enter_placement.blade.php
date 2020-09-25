<div class="popup-verify-placement">
    <div id="slot-placement-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-shield"></i> PLACEMENT</h4>
            </div>
            <div class="modal-body">
                <div class="message message-return-slot-placement-verify"></div>
                <form class="slot-placement-form">
                    <div>
                        <div class="labeld">Slot Placement</div>
                        <input class="input input-slot-placement text-center" name="slot_placement" type="text">
                        <input class="chosen_slot_id" name="chosen_slot_id" type="hidden" value="{{ $slot_info->slot_id }}">
                    </div>
                    <div>
                        <div class="labeld">Slot Position</div>
                        <select class="input input-slot-position text-center" name="slot_position" type="text" style="text-align-last:center;">
                        	<option value="left">LEFT</option>
                        	<option value="right">RIGHT</option>
                        </select>
                    </div>
                    <div class="btn-container text-center">
                        <div class="clearfix"><button type="submit" id="check_placement" class="btn-verify-placement">VERIFY PLACEMENT</button></div>
                        @if($iamowner)
                            <a class="iamowner" style="color: #422311; padding-top: 10px; font-weight: normal; font-size: 14px; display: block;" href="javascript:"><b>SKIP PLACEMENT</b> Allow my sponsor to position me later.</a>
                        @endif
                    </div>
                </form>
            </div>
          </div>
      </div>
</div>