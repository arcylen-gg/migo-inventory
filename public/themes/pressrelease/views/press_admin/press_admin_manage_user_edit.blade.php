@extends("press_admin.admin")
@section("pressview")
<div class="background-container">
    <div class="pressview">
        <div class="dashboard-container">
        <div class="media-container">
          <div class="tab">
            <button class="tablinks" onclick="openCity(event, 'user_account')" id="defaultOpen">Users Account</button>
            <button class="tablinks" onclick="openCity(event, 'admin_account')" >Admin Account</button>
          </div>
                                    
            <div class="press-release-content">
                  <form method="post"  action="/pressadmin/manage_user/add_admin" >
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
                 
            </div>
        </div>
    </div>
</div>

@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_admin_manage_user.css">
@endsection

@section("script")

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

