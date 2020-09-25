<form class="global-submit form-horizontal" role="form" action="/member/mlm/recaptcha/add_pool" method="post">
    {{csrf_field()}}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">{{$page}}</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="form-group">
            <div class="col-md-12">
                <label for="basic-input">Amount</label>
                <input autocomplete="off" id="basic-input" type="text" class="form-control" name="amount">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
        <button class="btn btn-primary btn-custom-primary" type="submit">Add</button>
    </div>
</form>
<script type="text/javascript">
    function success_pool(data)
    {
        toastr.success('Success');
        recaptcha.action_load_pool();
        data.element.modal('hide');
    }
    function error(data)
    {
        toastr.error('Error. Please try again');
    }
    function negative(data)
    {
        toastr.error('Error (Negative wallet)');
    }
</script>