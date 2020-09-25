<form id="bank-form" class="global-submit" method="POST" action="/mlm/slots/manual_add_slot_post">
    {!! csrf_field() !!}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Code</h4>
    </div>
    <div class="modal-body max-450 modallarge-body-layout background-white">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12">
                    <label>Membership Pin Code</label>
                    <input type="text" name="membership_code_id" class="form-control">
                </div>
                <div class="col-md-12">
                    <label>Membership Activation Code</label>
                    <input type="text" name="membership_activation_code" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="error-modal text-center"></div>
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit">Check</button>
    </div>
</form>