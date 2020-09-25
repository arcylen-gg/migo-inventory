@extends("press_admin.admin")
@section("pressview")
<div class="background-container">
    <div class="pressview">
        <div class="dashboard-container">
        <div class="media-container">
           @if (Session::has('success_admin'))
              <div class="alert alert-success">
                 <center>{{ Session::get('success_admin') }}</center>
              </div>
            @endif  
            @if (Session::has('success_user'))
              <div class="alert alert-success">
                 <center>{{ Session::get('success_user') }}</center>
              </div>
            @endif  
            @if (Session::has('delete_admin'))
              <div class="alert alert-danger">
                 <center>{{ Session::get('delete_admin') }}</center>
              </div>
            @endif
            @if (Session::has('delete_user'))
              <div class="alert alert-danger">
                 <center>{{ Session::get('delete_user') }}</center>
              </div>
            @endif    
          <div class="tab">
            <button class="tablinks" onclick="openCity(event, 'user_account')" id="defaultOpen">Users Account</button>
            <button class="tablinks" onclick="openCity(event, 'admin_account')" >Admin Account</button>
          </div>    

        <div class="press-release-content">
            <div id="user_account" class="tabcontent press-media-container">
              <div class="user-update-container">
                @if(session::has('edit_user'))
                 @foreach($_user_edit as $_user_edits)
                  <div class="title-container">UPDATE USER ACCOUNT</div>
                    <form method="post" action="">
                      {{csrf_field()}}
                      <div class="title">First Name:</div>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="{{$_user_edits->user_first_name}}" autofocus>

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

              <div class="user-container">
                 <div class="title">User Account
                        <div class="search-container pull-right">
                         <input placeholder="Search" type="text"  name="search_user" id="search_user">
                         <button  type="button" name="search_button_user" id="search_button_user" class="btn btn-success">Search</button>
                        </div>
                  </div>
                  <table  class="table table-bordered" id="showHere_table_search">
                     <tr>
                         <th style="width: 15%;">First Name</th>
                         <th style="width: 15%;">Last Name</th>
                         <th style="width: 15%;">Email</th>
                         <th style="width: 15%;">Company Name</th>
                         <th style="width: 20%;">Action</th>
                     </tr>
                      @foreach($_user as $_user_account)
                        <tr>
                           <td> <a href="">{{$_user_account->user_first_name}}</td>
                           <td>{{$_user_account->user_last_name}}</td>
                           <td>{{$_user_account->user_email}}</td>
                           <td>{{$_user_account->user_company_name}}</td>
                           <td>
                            <a id="edit" href="/pressadmin/edit_user/{{$_user_account->user_id}}"><button type="button"  class="btn btn-warning center"><i class="fa fa-wrench" name="recipient_id" aria-hidden="true"></i>  Edit</button></a>

                            <a href="/pressadmin/delete_user/{{$_user_account->user_id}}"><button type="button" class="btn btn-danger center">
                            <i class="fa fa-trash" name="recipient_id" aria-hidden="true"></i>  Delete</button></a>

                            <a href="/pressadmin/manage_force_login/{{$_user_account->user_id}}"><button type="button" class="btn btn-success center">
                            <i class="fa fa-vcard-o" name="recipient_id" aria-hidden="true"></i>  Force Login</button></a>
                           </td>
                        </tr>
                        @endforeach
                  </table>
              </div>    
            </div>

            <div id="admin_account" class="tabcontent add-media-container">
                <div class="title-container">Update Admin Account</div>
                  <table  class="table table-bordered" id="showHere_table">
                     <tr>
                         <th style="width: 20%;">First Name</th>
                         <th style="width: 20%;">Last Name</th>
                         <th style="width: 20%;">Username</th>
                         <th style="width: 20%;">Action</th>
                         
                     </tr>
                      @foreach($_admin as $_admin_account)
                        <tr>
                           <td>{{$_admin_account->user_first_name}}</td>
                           <td>{{$_admin_account->user_last_name}}</td>
                           <td>{{$_admin_account->user_email}}</td>
                           <td>
                            <a href="/pressadmin/edit_admin/{{$_admin_account->user_id}}"><button type="button"  class="btn btn-warning center">
                            <i class="fa fa-wrench" name="recipient_id" aria-hidden="true"></i>  Edit</button>

                            <a href="/pressadmin/manage_user/delete_admin/{{$_admin_account->user_id}}"><button type="button"  class="btn btn-danger center">
                            <i class="fa fa-trash" name="recipient_id" aria-hidden="true"></i>  Delete</button>
                           </td>
                        </tr>
                        @endforeach
                  </table>
                @if(session::has('edit_admin'))
                 @foreach($_admin_edit as $_admin_edits)
                     <div class="title-container">Add Admin Account</div>
                       <form method="post" action="">
                         {{csrf_field()}}
                         <div class="title">First Name:</div>
                       <input type="text" id="first_name" name="first_name" class="form-control" value="{{$_admin_edits->user_first_name}}" autofocus>

                         <div class="title">Last Name:</div>
                         <input type="text"  id="last_name" name="last_name" class="form-control" value="{{$_admin_edits->user_last_name}}">

                         <div class="title">Email:</div>
                         <input type="text" id="email" name="email" class="form-control" value="{{$_admin_edits->user_email}}">


                         <div class="title">Company:</div>
                         <input type="text" id="company_name" name="company_name" class="form-control" value="{{$_admin_edits->user_company_name}}">

                         <div class="button-container">
                             <button type="submit" id="submit_button" name="submit_button" formaction="/pressadmin/manage_admin_edit">Submit</button>
                         </div>
                     </form>
                    @endforeach
                   @else
                   <div class="title-container">Add Admin Account</div>
                     <form method="post" action="">
                       {{csrf_field()}}
                       <div class="title">First Name: </div>
                       <input type="text" id="user_first_name" name="user_first_name" class="form-control" required>

                       <div class="title">Last Name: </div>
                       <input type="text"  id="user_last_name" name="user_last_name" class="form-control" required>

                       <div class="title">Username: </div>
                       <input type="email" id="user_email" name="user_email" class="form-control" required>

                       <div class="title">Password: </div>
                       <input type="Password" id="user_password" name="user_password" class="form-control" required>

                       <div class="button-container">
                           <button type="submit" id="admin_submit_button" name="admin_submit_button" formaction="/pressadmin/manage_user/add_admin">Submit</button>
                       </div>
                   </form>
                   @endif
            </div>
        </div>
    </div>
</div>  



@endsection

@section("css") 
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_admin_manage_user.css">
@endsection

@section("script")
<script  src="/assets/js/manage_user.js"></script>

<script>
  $('#edit').click(function()
  {
    //alert("123");
    $('#previewPopup').modal('show');
</script>

<script>
function openCity(evt, cityName) 
{
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>

@endsection

