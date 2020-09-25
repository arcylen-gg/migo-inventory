<form class="global-submit" role="form" action="/member/mlm/developer/change_owner?slot_id={{ $id}}" method="post">
    {{ csrf_field() }}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">CHANGE SLOT OWNER</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input name="email" required id="basic-input" value="{{ $slot->email }}" class="form-control text-center" placeholder="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit">Save New Owner</button>
    </div>
</form>

<script type="text/javascript">
    function change_owner_success(data)
    {
        toastr.success("Change Owner Successful");
        mlm_developer.action_load_data();
        data.element.modal("hide");
    }
</script>