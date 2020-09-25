<form id="bank-form" class="global-submit" method="POST" action="/member/ecommerce/settings/updateBank">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <input type="hidden" name="bank_id" id="bank_id" value="{{$bank->ecommerce_banking_id}}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Update Bank details</h4>
    </div>
    <div class="modal-body max-450 modallarge-body-layout background-white">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12">
                    <label>Bank Name</label>
                    <input type="text" value="{{$bank->ecommerce_banking_name}}" name="bank_name" class="form-control" placeholder="Bank Name" required/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <label>Account Name</label>
                    <input type="text" name="account_name" value="{{$bank->ecommerce_banking_account_name}}" class="form-control" placeholder="Account Name" required/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <label>Account Number</label>
                    <input type="text" name="account_number" value="{{$bank->ecommerce_banking_account_number}}" class="form-control" placeholder="Account Number" required/>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="error-modal text-center"></div>
        
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit">Update Bank</button>
    </div>
  
</form>