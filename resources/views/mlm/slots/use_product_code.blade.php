<form class="global-submit" method="POST" action="{{$action}}">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Use Product Code</h4>
    </div>
    <div class="modal-body modallarge-body-layout background-white">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12">
                    <label>PIN NO.</label>
                    <input type="text" class="form-control" name="mlm_pin">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <label>ACTIVATION CODE</label>
                    <input type="text" class="form-control" name="mlm_activation">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary " type="submit">Validate</button>
    </div>
</form>
<script type="text/javascript">
    function success_validation(data) 
    {
        if(data.status == 'success')
        {
            action_load_link_to_modal('{{$confirm_action}}?mlm_pin='+data.mlm_pin+'&mlm_activation='+data.mlm_activation, 'md');
        }
    }
</script>