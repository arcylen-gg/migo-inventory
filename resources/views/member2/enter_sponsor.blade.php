<div class="popup-enter-a-code">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><i class="fa fa-star"></i> SPONSOR</h4>
    </div>
    <div class="modal-body">
        <form method="post" class="submit-verify-sponsor">

            <div class="labels">{!! $message !!}</b></div>
            
            @if($lock_sponsor)
                <input disabled required="required" class="input-verify-sponsor text-center" name="verify_sponsor" type="text" value="{{ $lock_sponsor }}">
            @else
                <input required="required" class="input-verify-sponsor text-center" name="verify_sponsor" type="text" placeholder="">
            @endif
            
            <div class="output-container">
                
            </div>

            <div class="btn-container">
                <button id="btn-verify" class="btn-verify btn-verify-sponsor"><i class="fa fa-check"></i> VERIFY SPONSOR</button>
            </div>
            
        </form>
    </div>
</div>