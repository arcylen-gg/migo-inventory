<form id="bank-form" class="global-submit" method="POST" action="/member/ecommerce/settings/insert_banking">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Create new Bank details</h4>
    </div>
    <div class="modal-body max-450 modallarge-body-layout background-white">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" required/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <label>Account Name</label>
                    <input type="text" name="account_name" class="form-control" placeholder="Account Name" required/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <label>Account Number</label>
                    <input type="text" name="account_number" class="form-control" placeholder="Account Number" required/>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="error-modal text-center"></div>
        
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit">Save Bank</button>
    </div>
  
</form>