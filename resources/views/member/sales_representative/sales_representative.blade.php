<form class="global-submit" method="post" action="/member/utilities/sales-representative-submit">
    <input type="hidden" value="{{ csrf_token() }}" name="_token">
    <input type="hidden" value="{{ $sales_rep_id or '' }}" name="sales_rep_id">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title item_title">{{strtoupper($action)}} Sales Representative</h4>
    </div>
    <div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
        <div class="panel-body form-horizontal">
            @if($migo_customization)
            <div class="form-group">
                <div class="col-md-12">
                    <select class="form-control select-employee" name="customer_id">
                        <option value="">No  Customer</option>
                        @foreach($_customer as $cust)
                            @if(!$cust->sales_rep_id || $sales_rep_id)
                            <option {{isset($sales_rep->sales_rep_customer_id) ? ($sales_rep->sales_rep_customer_id == $cust->customer_id ? 'selected' : 'ne') : 'no'}} value="{{$cust->customer_id}}" fname="{{$cust->first_name}}" mname="{{$cust->middle_name}}" lname="{{$cust->last_name}}" contact-num="{{$cust->customer_mobile}}" address="{{$cust->customer_street .' '.$cust->customer_city .' '.$cust->customer_state}}">{{$cust->first_name .' '. $cust->middle_name.' '. $cust->last_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            <div class="form-group">
              <div class="col-md-4">
                  <input type="text" value="{{$sales_rep->sales_rep_first_name or ''}}" class="form-control input-fname" name="fname" placeholder="First name *"> 
              </div>
              <div class="col-md-4">
                  <input type="text" value="{{$sales_rep->sales_rep_middle_name or ''}}" class="form-control input-mname" name="mname" placeholder="Middle name">
              </div>
              <div class="col-md-4">
                  <input type="text" value="{{$sales_rep->sales_rep_last_name or ''}}" class="form-control input-lname" name="lname" placeholder="Last name *">
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-6">
                  <input type="text" value="{{$sales_rep->sales_rep_employee_number or ''}}" class="form-control" name="employee_num" placeholder="Employee number *">
              </div>
              <div class="col-md-6">
                  <input type="text" value="{{$sales_rep->sales_rep_contact_no or ''}}" class="form-control input-cnum" name="contact_num" placeholder="Contact Number *">
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12">
                    <textarea class="form-control input-address" placeholder="Address *" name="address">{{$sales_rep->sales_rep_address or ''}}</textarea>
              </div>
            </div>
        </div>
    </div>
    <div class="modal-footer" >
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" >Save</button>
    </div>
</form>
<script type="text/javascript">
    $('.select-employee').on('change', function()
    {
        $('.input-fname').val($(this).find('option:selected').attr('fname'));
        $('.input-mname').val($(this).find('option:selected').attr('mname'));
        $('.input-lname').val($(this).find('option:selected').attr('lname'));
        $('.input-cnum').val($(this).find('option:selected').attr('contact-num'));
        $('.input-address').html($(this).find('option:selected').attr('address'));
    });
</script>