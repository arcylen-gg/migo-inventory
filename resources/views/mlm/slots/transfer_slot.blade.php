<form id="bank-form" class="global-submit" method="POST" action="/mlm/slots/transfer_slot_post">
    {!! csrf_field() !!}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Transfer Slot</h4>
    </div>
    <div class="modal-body max-450 modallarge-body-layout background-white">
        <div class="form-group">
            <label>Slot</label>
            <input type="text" class="form-control" value="{{$slot->slot_no}}" readonly>
            <input type="hidden" name="slot_id" class="form-control" value="{{$encrypted}}" readonly>
        </div>
        <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password">
        </div>
        <div class="form-group">
                <label>Transfer to(Username)</label>
                <input type="text" class="form-control" name="mlm_username">
        </div>
    </div>
    <div class="modal-footer">
        <div class="error-modal text-center"></div>
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit">Transfer</button>
    </div>
</form>