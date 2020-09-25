<form id="remittance-form" class="global-submit" action="/member/ecommerce/settings/updateremittance" method="POST">
    <input type="hidden" value="{{csrf_token()}}" name="_token">
    <input type="hidden" name="remittance_id" value="{{$remittance->ecommerce_remittance_id}}">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title">Update Remittance details</h4>
    </div>
    <div class="modal-body max-450 modallarge-body-layout background-white">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12">
                    <label>Remittance Name/Center</label>
                    <input type="text" value="{{$remittance->ecommerce_remittance_name}}" name="remittance_name" class="form-control" placeholder="Remittance Name/Center" required/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <label>Full Name</label>
                    <input type="text" value="{{$remittance->ecommerce_remittance_full_name}}" name="account_name" class="form-control" placeholder="Full Name" required/>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <label>Address</label>
                    <textarea class="form-control" placeholder="Address" name="address" required>{{$remittance->ecommerce_remittance_address}}</textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" value="{{$remittance->ecommerce_remittance_contact}}" class="form-control" placeholder="Contact Number" required/>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="error-modal text-center"></div>
        
        <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
        <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
        <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Update</button>
    </div>
  
</form>