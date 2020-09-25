<form class="global-submit" method="POST" action="{{$action}}">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <input type="hidden" value="{{$mlm_pin}}" name="mlm_pin">
    <input type="hidden" value="{{$mlm_activation}}" name="mlm_activation">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Choose Slot</h4>
    </div>
    <div class="modal-body modallarge-body-layout background-white">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12">
                    <label>Select Slot</label>
                    <select class="form-control" name="slot_no">
                        @foreach($_slot as $slot)
                        <option value="{{$slot->slot_no}}">{{$slot->slot_no}} ({{$slot->membership_name}})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary " type="submit">Select</button>
    </div>
</form>
<script type="text/javascript">
    function success_slot(data) 
    {
        if(data.status == 'success')
        {
            action_load_link_to_modal('{{$confirm_action}}?mlm_pin='+data.mlm_pin+'&mlm_activation='+data.mlm_activation+'&slot_no='+data.slot_no, 'md');
        }
    }
</script>