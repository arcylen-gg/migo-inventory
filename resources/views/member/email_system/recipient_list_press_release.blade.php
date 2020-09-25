@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css'>
<link rel="stylesheet" type="text/css" href="/email_assets/email_css/create_email.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-envelope"></i>
            <h1>
                <span class="page-title">Press Release Email System</span>
            </h1>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <header class="header_email">
    <div class="panel-body form-horizontal">
        <div class="form-group">
           <h1 class="create_news">Recipient List</h1>
        </div>     
    </div>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> Add Recipient</button>
    </header>
    <table  class="table table-hover">
  <thead>
    <tr>
      <th>Name</th>
      <th>Company Name</th>
      <th>Position</th>
      <th>Title of Journalist</th>
      <th>Country</th>
      <th>Industry Type</th>
    </tr>
  </thead>
  <tbody>
    @foreach($_list_recipient as $list_recipient)
    <tr>
      <td>{{$list_recipient->name}}</td>
      <td>{{$list_recipient->company_name}}</td>
      <td>{{$list_recipient->position}}</td>
      <td>{{$list_recipient->title_of_journalist}}</td>
      <td>{{$list_recipient->country}}</td>
      <td>{{$list_recipient->industry_type}}</td>
    </tr>
    </tr>
    </tr>
    @endforeach
  </tbody>
</table>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Recipient Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action ="/member/page/press_release_email/add_recipient_press_release" id="recipient-form">
          {{csrf_field()}}
          <div class="form-group">
            <label for="recipient-name" class="form-control-label">Recipient Name:</label>
            <input type="text" class="form-control" id="recipient-name" name="recipient_name">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="form-control-label">Recipient Email Address:</label>
            <input type="text" class="form-control" id="recipient-email" name="recipient_email_address">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="form-control-label">Recipient Position:</label>
            <input type="text" class="form-control" id="recipient-position" name="recipient_position">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="form-control-label">Group Name:</label>
            <input type="text" class="form-control" id="group_name" name="group_name">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save">Save Message</button>
      </div>
    </div>
  </div>
</div>
<script>
  $('.save').click(function(event) {
    var name = $('#recipient-name').val();
    var email = $('#recipient-email').val();
    var position = $('#recipient-position').val();
    var group = $('#group_name').val();
    var token = '{{csrf_token()}}';
    alert(token);
    $.ajax({
         type: "POST",
         url: '/member/page/press_release_email/add_recipient_press_release',
         data: {_token:token,name:name,email:email,position:position,group:group},
              success: function(response){
             if(response == 'success')
             {
              /*$('#myModal_email').modal('hide');*/
              /*$('.from_email').val('');
              $('.to_email').val('');*/
              alert('message save');
              $('#exampleModal').modal('hide');
             }
         }
    });
  });
</script>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
@endsection   

