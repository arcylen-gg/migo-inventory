<form class="global-submit" role="form" action="/member/mlm/payout/edit?id={{request("id")}}" method="post">
    {{csrf_field()}}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title">UPDATE PAYOUT DETAILS</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="basic-input">Payout Amount</label>
                            <input id="basic-input" value="{{$payout->wallet_log_request}}" class="form-control text-right" name="wallet_log_request" placeholder="">
                        </div>
                        <div class="col-md-6">
                            <label for="basic-input">Method</label>
                            <input id="basic-input" value="{{$payout->wallet_log_plan}}" class="form-control text-right" name="wallet_log_plan" placeholder="">
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-md-6">
                            <label for="basic-input">Tax</label>
                            <input id="basic-input" value="{{$payout->wallet_log_tax}}" class="form-control text-right" name="wallet_log_tax" placeholder="">
                        </div>
                        <div class="col-md-6">
                            <label for="basic-input">Service Charge</label>
                            <input id="basic-input" value="{{$payout->wallet_log_service_charge}}" class="form-control text-right" name="wallet_log_service_charge" placeholder="">
                        </div>
                    </div>  
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Password</label>
                            <input id="basic-input" value="" class="form-control text-right" name="password" placeholder="">
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
    function update_payout_success(data)
    {
        data.element.modal("hide");
        toastr.success("Success!");
        payout.action_load_table();
    }
</script>