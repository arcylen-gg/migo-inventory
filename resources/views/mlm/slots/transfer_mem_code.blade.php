<form id="bank-form" class="global-submit" method="POST" action="/mlm/slots/transfer_mem_code_post">
    {!! csrf_field() !!}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Code</h4>
    </div>
    <div class="modal-body max-450 modallarge-body-layout background-white">
        <div class="form-group">
                <label>Membership Code</label>
                <input type="text" class="form-control" value="{{$mem->membership_activation_code}} {{$mem->membership_type}} ({{$mem->membership_name}})" readonly>
                <input type="hidden" class="form-control" name="membership_activation_code" value="{{$mem->membership_activation_code}}">
        </div>
        <div class="form-group">
                <label>Transfer to</label>
                <select name="customer_id" class="form-control">
                    @foreach($_customer as $customer)
                        <option value="{{$customer->child_customer_id}}">{{$customer->first_name}} {{$customer->middle_name}} {{$customer->last_name}}</option>
                    @endforeach
                </select>
        </div>
    </div>
    <div class="modal-footer">
        <div class="error-modal text-center"></div>
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit">Transfer Code</button>
    </div>
</form>