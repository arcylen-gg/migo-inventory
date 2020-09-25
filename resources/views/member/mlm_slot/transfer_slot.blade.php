<form id="bank-form" class="global-submit" method="POST" action="/member/mlm/slot/transfer_post">
    {!! csrf_field() !!}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Transfer Slot</h4>
    </div>
    <div class="modal-body max-450 modallarge-body-layout background-white">
        <div class="form-group">
            <label>Slot</label>
            <input type="text" class="form-control" value="{{$slot->slot_no}}" readonly>
            <input type="hidden" class="form-control" name="slot_id" value="{{$slot->slot_id}}" readonly>
        </div>
        <div class="form-group">
                <label>Transfer to</label>
                <select class="form-control chosen-select" name="slot_owner">
                    @foreach($_customer as $customer)
                        <option value="{{$customer->customer_id}}">{{$customer->first_name}} {{$customer->middle_name}} {{$customer->last_name}}</option>
                    @endforeach
                </select>
        </div>
    </div>
    <div class="modal-footer">
        <div class="error-modal text-center"></div>
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit">Transfer</button>
    </div>
</form>