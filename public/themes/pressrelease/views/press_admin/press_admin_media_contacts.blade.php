@extends("press_admin.admin")
@section("pressview")
<div class="background-container">
    <div class="pressview">
        <div class="dashboard-container">
        <div class="media-container">
          <div class="tab">
            <button class="tablinks" onclick="openCity(event, 'press_media')" id="defaultOpen">Press IQ Media Contacts</button>
            <button class="tablinks" onclick="openCity(event, 'add_media')" >Add Media Contacts</button>
          </div>
                                    
            <div class="press-release-content">
            <div id="press_media" class="tabcontent press-media-container">
                 @if (Session::has('success_merchant'))
                  <div class="alert alert-success">
                     <center>{{ Session::get('success_merchant') }}</center>
                  </div>
                  @endif 
                  @if (Session::has('delete'))
                  <div class="alert alert-danger">
                     <center>{{ Session::get('delete') }}</center>
                  </div>
                  @endif

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
                          @foreach($_media_contacts as $_media)
                            <tr>
                               <td>{{$_media->name}}</td>
                               <td>{{$_media->company_name}}</td>
                               <td>{{$_media->country}}</td>
                               <td>
                                 <a href="/pressadmin/pressreleases_edit_recipient/{{$_media->recipient_id}}"><button type="button"  class="btn btn-warning center">
                                 <i class="fa fa-wrench" name="" aria-hidden="true"></i>Edit</button>
                                 
                                 <a href="/pressadmin/pressreleases_deleterecipient/{{$_media->recipient_id}}"><button type="button"  class="btn btn-danger center">
                                 <i class="fa fa-trash" name="recipient_id" aria-hidden="true"></i>Delete</button>
                               </td>
                            </tr>
                            @endforeach
                         
                     </table>
                   </div>
                 </div>    
                </div>
            </div>

            <div id="add_media" class="tabcontent add-media-container">
              @if(session()->has("r_edit"))
                @foreach($edit as $edits)
                <form method="post" action="/pressadmin/pressreleases_addrecipient">
                    {{csrf_field()}}
                    <div class="title">Contact Name: *</div>
                    <input type="text" id="name" name="name" class="form-control" value="{{$edits->name}}" required>

                    <div class="title">Position: *</div>
                    <input type="text"  id="position" name="position" class="form-control" value="{{$edits->position}}" required>

                    <div class="title">Company Name: *</div>
                    <input type="text" id="company_name" name="company_name" class="form-control" value="{{$edits->company_name}}" required>


                    <div class="title">Country: *</div>
                    <input type="text" id="country" name="country" class="form-control" value="{{$edits->country}}" required>

                    <div class="title">Email: *</div>
                    <input type="email" id="contact_email" name="contact_email" class="form-control" value="{{$edits->research_email_address}}" required>

                    <div class="title">Website: *</div>
                    <input type="text"  id="contact_website" name="contact_website" class="form-control" value="{{$edits->website}}">

                    <div class="title">Media Type: *</div>
                    <input type="text" id="media_type" name="media_type" class="form-control" value="{{$edits->media_type}}">

                    <div class="title">Industry Type: *</div>
                    <input type="text" id="industry_type" name="industry_type" class="form-control" value="{{$edits->industry_type}}">

                    <div class="title">Title Journalist: *</div>
                    <input type="text" id="title_journalist" name="title_journalist" class="form-control" value="{{$edits->title_of_journalist}}">

                    <div class="title">Description: *</div>
                    <textarea id="description" name="description">{{$edits->description}}</textarea>
                    <div class="button-container">
                        <button type="submit" id="submit_button" name="submit_button">Add Contacts</button>
                    </div>
                </form>
                @endforeach
              @else
                <form method="post" action="/pressadmin/pressreleases_addrecipient">
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
              @endif
            </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_admin_media_contacts.css">
@endsection

@section("script")
<script  src="/assets/js/media_contacts.js"></script>
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

