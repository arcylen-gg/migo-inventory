<div data-page="report" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="index.html" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Enter Placement</div>
            <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
        </div>
    </div>
    <div class="page-content">
        <div class="enter-placement-container">
            <div class="message message-return-slot-placement-verify"></div>
            <form class="slot-placement-form">
                <div>
                    <div class="labeld">Slot Placement</div>
                    <input class="input input-slot-placement text-center" name="slot_placement" type="text" placeholder="Enter Slot Code of Placement">
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
                    <div class="clearfix"><button type="submit" id="check_placement" class="btn-verify-placement"><i class="fa fa-angle-double-right"></i> PROCEED</button></div>
                    @if($iamowner)
                        <!--<a class="iamowner" style="color: #422311; padding-top: 10px; font-weight: normal; font-size: 14px; display: block;" href="javascript:"><b>SKIP PLACEMENT</b> Allow my sponsor to position me later.</a>-->
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>