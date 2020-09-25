<form class="global-submit" action="/member/mlm/code/sell/add_line/submit" method="post">
    {!! csrf_field() !!}
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title">Add New Line</h4>
  </div>
  <div class="modal-body max-450 modallarge-body-layout background-white">
      <div class="col-md-5">
          <label for="membership_package">Package</label>
          {!! $membership_package !!}
      </div>
      <div class="col-md-3">
          <label for="membership_type">Membership Type:</label>
          {!! $membership_type !!}
      </div>
      <div class="col-md-4">
          <label for="quantiy">Quantity:</label>
          <input type="number" class="form-control" name="quantiy" value="1">
      </div>
      <div class="col-md-12"><br></div>
      <div class="col-md-12" id="add-new-line-warning"></div>
  </div>
  <div class="modal-footer">
    <!--<div class="error-modal text-center">-->
    <!--    Error-->
    <!--</div>-->
    
    <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
    <button class="btn btn-primary">Submit</button>
  </div>
</form>
<script type="text/javascript">
//   function loading_done()
//   {
//       alert(url);
//   }
// load_session();


    
</script>