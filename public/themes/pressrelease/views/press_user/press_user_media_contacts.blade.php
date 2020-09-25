@extends("press_user.member")
@section("pressview")
<div class="background-container">
<div class="pressview">
        <div class="dashboard-container">
        <div class="media-container">
          @if (Session::has('success_user'))
          <div class="alert alert-success">
             <center>{{ Session::get('success_user') }}</center>
          </div>
          @endif                        
          @if (Session::has('delete_user'))   
          <div class="alert alert-danger">
             <center>{{ Session::get('delete_user') }}</center>
          </div>
          @endif
          <div class="tab">
            <button class="tablinks" onclick="openCity(event, 'add_media')" id="defaultOpen">Add Media Contacts</button>
            <button class="tablinks" onclick="openCity(event, 'press_user')" >Media Contacts</button>
          </div>                                    
            <div class="press-release-content">
                <div id="add_media" class="tabcontent add-media-container">
                    <form method="post" action="/pressuser/media_contacts/add">
                        {{csrf_field()}}
                        <div class="title">Contact Name: *</div>
                        <input type="text" id="name" name="name" class="form-control" required>

                        <div class="title">Position: *</div>
                        <input type="text"  id="position" name="position" class="form-control" required>

                        <div class="title">Company Name: *</div>
                        <input type="text" id="company_name" name="company_name" class="form-control" required>

                        <div class="title">Country: *</div>
                        <input type="text" id="country" name="country" class="form-control" required>

                        <div class="title">Email: *</div>
                        <input type="email" id="contact_email" name="contact_email" class="form-control" required>

                        <div class="title">Website: *</div>
                        <input type="text"  id="contact_website" name="contact_website" class="form-control">

                        <div class="title">Media Type: *</div>
                        <input type="text" id="media_type" name="media_type" class="form-control">

                        <div class="title">Industry Type: *</div>
                        <input type="text" id="industry_type" name="industry_type" class="form-control" >

                        <div class="title">Title Journalist: *</div>
                        <input type="text" id="title_journalist" name="title_journalist" class="form-control">

                        <div class="title">Description: *</div>
                        <textarea id="description" name="description"></textarea>
                        <div class="button-container">
                            <button type="submit" id="submit_button" name="submit_button">Add Contacts</button>
                        </div>
                    </form>
                </div>

                <div id="press_user" class="tabcontent press-media-container">
                    <div class="col-md-12">
                     <div class="press-holder-container">
                        <div class="title-container">Media Contacts
                            <div class="search-container pull-right">
                             <input placeholder="Search" type="text"  name="search_media" id="search_media">
                             <button  type="button" name="search_button" id="search_button" class="btn btn-success">Search</button>
                            </div>   
                        </div>
                       <div class="left-container" name="press_table" id="press_table1">
                         <table  class="table table-bordered" id="showHere_table1">
                             <tr>
                                 <th style="width: 25%;">Contact Name</th>
                                 <th style="width: 25%;">Company</th>
                                 <th style="width: 25%;">Country</th>
                                 <th style="width: 25%;">Action</th>
                             </tr>
                            @foreach($user_media_contacts as $_user)
                            <tr>
                               <td style="width: 25%;">{{$_user->name}}</td>
                               <td style="width: 25%;">{{$_user->company_name}}</td>
                               <td style="width: 25%;">{{$_user->country}}</td>
                               <td style="width: 25%;">
                                 <a href=""><button type="button"  class="btn btn-warning center">
                                 <i class="fa fa-wrench" name="" aria-hidden="true"></i>Edit</button>
                                 
                                 <a href="/pressuser/media_contacts/delete/{{$_user->recipient_id}}"><button type="button"  class="btn btn-danger center">
                                 <i class="fa fa-trash" name="recipient_id" aria-hidden="true"></i>Delete</button>
                               </td>
                            </tr>
                            @endforeach
                         </table>
                       </div>
                     </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_user_media_contacts.css">
@endsection

@section("script")
<script  src="/assets/js/media_contacts_user.js"></script>
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

