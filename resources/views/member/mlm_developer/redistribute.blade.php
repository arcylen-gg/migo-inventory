<form class="global-submit" role="form" action="/member/mlm/developer/redistribute" method="post">
    {{ csrf_field() }}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">SLOT RE-DISTRIBUTE</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Slot No.</label>
                            <input required id="basic-input" value="" class="form-control text-center" name="slot_no" placeholder="">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit">Submit</button>
    </div>
</form>

<script type="text/javascript">
    function redistribute_success(data)
    {
        toastr.success("Re-Distribution Successful");
        data.element.modal("hide");
    }
</script>