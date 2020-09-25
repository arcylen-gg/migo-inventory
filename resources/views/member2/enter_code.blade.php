<div class="popup-proceed2">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><i class="fa fa-shield"></i> CODE VERIFICATION</h4>
        </div>
        <div class="modal-body">
            <div class="message message-return-code-verify"></div>
            <form class="code-verification-form">
                <div>
                    <div class="labeld">Pin Code</div>
                    <input class="input input-pin text-center" name="pin" type="text" value="{{$mlm_pin or ''}}">
                </div>
                <div>
                    <div class="labeld">Activation</div>
                    <input class="input input-activation text-center" name="activation" type="text" value="{{$mlm_activation or ''}}">
                </div>
                <div class="btn-container">
                    <button id="btn-proceed-2" class="btn-proceed-2"><i class="fa fa-angle-double-right"></i> Proceed</button>
                </div>
            </form>
        </div>
    </div>
</div>