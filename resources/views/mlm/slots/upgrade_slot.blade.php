<form id="bank-form" class="global-submit" method="POST" action="/mlm/slots/upgrade_slot_post/{{$id}}">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Upgrade Slot</h4>
    </div>
    <div class="modal-body max-450 modallarge-body-layout background-white">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12">
                @if($membership_code_count != 0)
                    <label>Membership Code</label>
                    <select name="membership_code_id" class="form-control">
                    @foreach($membership_code as $code)
                        <option value="{{$code->membership_code_id}}">{{$code->membership_activation_code}} - {{$code->membership_package_name}}</option>
                    @endforeach
                    </select>
                @else
                    <label>No membership code available for upgrading this slot.</label>
                @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="error-modal text-center"></div>
        
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
        @if($membership_code_count != 0)
            <button class="btn btn-custom-primary btn-save-modallarge" type="submit">Confirm</button>
        @endif
    </div>
</form>