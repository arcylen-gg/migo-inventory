<form class="global-submit" role="form" action="/member/usecode" method="post">
    {{ csrf_field()  }}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title redeemable-add-page-title">{{$page}}</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Pin</label>
                            <input id="basic-input"  class="form-control" name="pin" value=" {{$pin}} ">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Activation</label>
                            <input id="basic-input"  class="form-control" name="activation" value=" {{$activation}} ">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit">Save</button>
    </div>
</form>

<script type="text/javascript">
function success_use_code(data)
{
    toastr.success("success");
    data.element.modal("hide");
    redeemable.action_load_table();
}
</script>