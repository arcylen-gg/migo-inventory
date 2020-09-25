<!-- <div class="modal fade" id="myModal" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Modal Header</h4>
    </div> -->
    <div class="modal-body">
      @if(session::has('edit_user'))
       @foreach($_user_edit as $_user_edits)
        <label>UPDATE USER ACCOUNT</label>
          <form method="post" action="">
            {{csrf_field()}}
            <div class="title">First Name:</div>
          <input type="text" id="first_name" name="first_name" class="form-control" value="{{$_user_edits->user_first_name}}">

            <div class="title">Last Name:</div>
            <input type="text"  id="last_name" name="last_name" class="form-control" value="{{$_user_edits->user_last_name}}">

            <div class="title">Email:</div>
            <input type="text" id="email" name="email" class="form-control" value="{{$_user_edits->user_email}}">


            <div class="title">Company Name:</div>
            <input type="text" id="company_name" name="company_name" class="form-control" value="{{$_user_edits->user_company_name}}">


            <div class="button-container">
                <button type="submit" id="submit_button" name="submit_button" formaction="/pressadmin/manage_user_edit">Submit</button>
            </div>
        </form>
       @endforeach
      @endif
    </div>
    <!-- <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
 </div>
</div>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->