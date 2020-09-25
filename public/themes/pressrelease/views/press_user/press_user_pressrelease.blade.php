@extends("press_user.member")
@section("pressview")
<script  src="/assets/js/ajax_offline.js"></script>
<script  src="/assets/js/press_realease.js"></script>
<div class="background-container">
   <div class="pressview">
      <div class="dashboard-container">
          @if (session('message'))
            <div class="alert alert-danger">
                {{ session('message') }}
            </div>
          @endif
         <div class="press-release-container">
            <div class="tab"  style="border-style: none;">
               <button class="tablinks" onclick="openCity(event, 'create_release')" id="defaultOpen">Create New Release</button>
               <button class="tablinks" onclick="openCity(event, 'choose_recipient')" id="">Choose Recipients</button>
               <button class="tablinks" onclick="openCity(event, 'send_release')" id="">Send Release</button>
               <!-- <button class="tablinks" onclick="openCity(event, 'summary')" id="">Summary</button> -->
            </div>
           
            <div class="press-release-content">
              <form class="recipient_form" onsubmit="add_event_global_submit()" action="/pressuser/choose_recipient" method="POST" style="">
                {{csrf_field()}}
        
                <div id="create_release" class="tabcontent create-release-container">
                  <div class="title-container">New Release</div>
                    @if(session()->has("pr_edit"))
                      @foreach($edit as $edits)
                  <div class="title">Type1:</div>
                   <select name="pr_type" id="pr_type" style="width: 80% !important;">
                     <option>--Select option--</option>
                     @if($edits->pr_type=="Media Release")
                     <option selected value="Media Release">Media Release</option>
                     <option value="Press Release">Press Release</option>
                     <option value="Invitation">Invitation</option>
                     @elseif($edits->pr_type=="Press Release")
                     <option value="Media Release">Media Release</option>
                     <option selected value="Press Release">Press Release</option>
                     <option value="Invitation">Invitation</option>
                     @elseif($edits->pr_type=="Invitation")
                     <option value="Media Release">Media Release</option>
                     <option value="Press Release">Press Release</option>
                     <option selected value="Invitation">Invitation</option>
                     @else
                     <option value="Media Release">Media Release</option>
                     <option value="Press Release">Press Release</option>
                     <option value="Invitation">Invitation</option>
                     @endif
                   </select> 
                  <div class="title">Title:</div>
                        <input type="text" id="pr_headline" name="pr_headline" class="form-control" value="{{$edits->pr_headline}}" autofocus>
                  <div class="title">Release Text Body:</div>
                        <textarea name="pr_content" id="pr_content">{!!$edits->pr_content!!}</textarea>
                  <div class="title">Boilerplate:</div>
                        <textarea name="pr_boiler_content" id="pr_boiler_content">{!!$edits->pr_boiler_content!!}</textarea>
                  <div class="button-container">
                    <span class="button"><button type="submit" name="draft" value="draft" formaction="/pressuser/pressrelease/draft"><a>Save as draft</a></button></span>
                    <span class="preview-button"><a href="#" id="prev_btn">Preview</a></span>
                    <span class="button"><button type="button" id="btnNext" class="tablinks" onclick="openCity(event, 'choose_recipient')"><a>Continue &raquo;</a></button></span>
                  </div>
                </div>

                <div id="choose_recipient" class="tabcontent choose-recipient-container">
                    <div class="title-container">Choose Recipient</div>

                    <div class="title">Country:</div>
                    <select data-placeholder="--Choose a country--" multiple class="chosen-select" id="choose_country" name="choose_country[]">
                         @foreach($_country as $country_name)
                         <option value="{{$country_name->country}}">{{$country_name->country}}</option>
                         @endforeach
                    </select>

                    <div class="title">Industry Type:</div>
                    <select data-placeholder="--Choose a industry type--" multiple  class="chosen-select" id="industry_type" name="industry_type[]">
                          @foreach($_industry_type as $industry)
                        <option value="{{$industry->industry_type}}">{{$industry->industry_type}}</option>
                          @endforeach
                    </select>

                    <div class="title">Media Type:</div>
                    <select data-placeholder="--Choose a media type--" multiple class="chosen-select" id="media_type" name="media_type[]">
                          @foreach($_media_type as $media)
                        <option value="{{$media->media_type}}">{{$media->media_type}}</option>
                          @endforeach
                    </select>

                    <div class="title">Title of Journalist:</div>
                    <select data-placeholder="--Choose a title of journalist--" multiple class="chosen-select" id="title_of_journalist" name="title_of_journalist[]">
                          @foreach($_title_of_journalist as $title)
                        <option value="{{$title->title_of_journalist}}">{{$title->title_of_journalist}}</option>
                          @endforeach
                    </select>

                    <div class="title">Send To:
                    <span class="result-container" style="font-size:15px"><span id="results_number" style="font-size:15px"></span></span>
                    </div>
                     
                    <input type="hidden"  id="recipient_name" name="pr_receiver_name"  class="form-control" value="{{$edits->pr_receiver_name}}" multiple readonly>
                    
                    {{-- POPUP CHOOSE RECIPIENT --}}
                    <span class="choose-button" readon><a href="javascript:" id="pop_recipient_btn">Choose Recipient</a></span>

                    {{-- POPUP CHOOSE RECIPIENT --}}
                    <span class="button"><button type="button" id="btnNext" class="tablinks" onclick="openCity(event, 'send_release')"><a>Continue To Send &raquo;</a></button></span>
                    <input type="hidden" name="pr_to" id="recipient_email" class="form-control" value="{{$edits->pr_to}}" readonly >
                    @endforeach
                      @else
                  <div class="title">Type:</div>
                   <select name="pr_type" id="pr_type" class="form-control" style="width: 80% !important;">
                     <option>--Select option--</option>
                     <option value="Media Release">Media Release</option>
                     <option value="Press Release">Press Release</option>
                     <option value="Invitation">Invitation</option>
                   </select> 
                  <div class="title">Title:</div>
                        <input type="text" id="pr_headline" name="pr_headline" class="form-control" autofocus>
                  <div class="title">Release Text Body:</div>
                        <textarea name="pr_content" id="pr_content"></textarea>
                  <div class="title">Boilerplate:</div>
                        <textarea name="pr_boiler_content" id="pr_boiler_content"></textarea>
                  <div class="button-container">
                    <span class="button"><button type="submit" name="draft" value="draft" formaction="/pressuser/pressrelease/draft"><a>Save as draft</a></button></span>
                    <span class="preview-button"><a href="#" id="prev_btn">Preview</a></span>
                    <span class="button"><button type="button" id="btnNext" class="tablinks" onclick="openCity(event, 'choose_recipient')"><a>Continue &raquo;</a></button></span>
                  </div>
                </div>

                <div id="choose_recipient" class="tabcontent choose-recipient-container">
                    <div class="title-container">Choose Recipient</div>

                    <div class="title">Country:</div>
                    <select data-placeholder="--Choose a country--" multiple class="chosen-select" id="choose_country" name="choose_country[]">
                         @foreach($_country as $country_name)
                         <option value="{{$country_name->country}}">{{$country_name->country}}</option>
                         @endforeach
                    </select>

                    <div class="title">Industry Type:</div>
                    <select data-placeholder="--Choose a industry type--" multiple  class="chosen-select" id="industry_type" name="industry_type[]">
                          @foreach($_industry_type as $industry)
                        <option value="{{$industry->industry_type}}">{{$industry->industry_type}}</option>
                          @endforeach
                    </select>

                    <div class="title">Media Type:</div>
                    <select data-placeholder="--Choose a media type--" multiple class="chosen-select" id="media_type" name="media_type[]">
                          @foreach($_media_type as $media)
                        <option value="{{$media->media_type}}">{{$media->media_type}}</option>
                          @endforeach
                    </select>

                    <div class="title">Title:</div>
                    <select data-placeholder="--Choose a title of journalist--" multiple class="chosen-select" id="title_of_journalist" name="title_of_journalist[]">
                          @foreach($_title_of_journalist as $title)
                        <option value="{{$title->title_of_journalist}}">{{$title->title_of_journalist}}</option>
                          @endforeach
                    </select>

                    <div class="title">Send To:
                    <span class="result-container" style="font-size:15px"><span id="results_number" style="font-size:15px"></span></span>
                    </div>
                    <input type="hidden"  id="recipient_name" name="pr_receiver_name"  class="form-control" multiple readonly>
                    
                    {{-- POPUP CHOOSE RECIPIENT --}}
                    <span class="choose-button" readon><a href="javascript:" id="pop_recipient_btn">Choose Recipient</a></span>
                    {{-- POPUP CHOOSE RECIPIENT --}}
                    <span class="button"><button type="button" id="btnNext" class="tablinks" onclick="openCity(event, 'send_release')"><a>Continue &raquo;</a></button></span>
                      <input type="hidden" name="pr_to" id="recipient_email" class="form-control" readonly >
                      @endif
                    <div class="button-container"></div>
                </div>

                <div id="send_release" class="tabcontent send-release-container">
                  <div class="title-container">Send Release</div>
                  <div class="title">Publisher:</div>
                  <div class="content">{{session('user_first_name')}} {{session('user_last_name')}}</div>
                  <div class="title">Company:</div>
                  <div class="content">{{session('user_company_name')}}</div>
                  <div class="title">Title:</div>
                  <div class="content" id="headline_pr"></div>
                  <div class="title">Send To:</div>
                  <span class="result-container" style="font-size:15px"><span id="results_number_sendto" style="font-size:15px"></span></span>
                  <a href="javascript:" id="pop_chosen_recipient_btn">  VIEW</a>
                  <div class="button-container">
                    <button class="preview-button" type="button"  id="prev_btn_send">Preview</button>
                    <button type="submit" formaction="/pressuser/pressrelease/pr">Send &raquo;</button>
                  </div>
                </div>
              </form>
            </div>
         </div>
      </div>
   </div>
</div>
  <!-- Preview Popup -->
<div class="popup-preview">
  <div class="modal" id="previewPopup" name="previewPopup" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Preview</h4>
        </div>
           
        <div class="modal-body">
          <div class="row-no-padding clearfix">
            <div class="col-md-9">
              <div id="preview_headline"></div>
            </div>
            <div class="col-md-3">
              <div class="logo-holder">
                <img src="{{session('user_company_image')}}">
              </div>
            </div>
          </div>
          <div id="preview_content"></div>
          <div class="about-title">
            <div>About {{session('user_company_name')}}
              <input type="datetime"  value="<?php echo date("Y-m-d\ H:i:s",time()); ?>"/ style="border: none;" readonly></div>
              <div id="preview_type"></div>
          </div>
          <div id="preview_boiler_content"></div>
            <div>
              &nbsp; <a href="https://twitter.com/share" class="twitter-share-button" data-url="" data-size="large">Tweet</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
              
              <iframe src="https://www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook/press-iq.com&width=74&layout=button_count&action=like&size=large&show_faces=false&share=false&height=21&appId" width="74" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
  </div>
</div>


@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_user_pressrelease.css">
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
   
   $(".chosen-select").chosen({disable_search_threshold: 10});
</script>

<script src="/email_assets/tinymce/js/tinymce/tinymce.min.js"></script>
<script src="/email_assets/tinymce/js/tinymce/tinymce.js"></script>
<script src="/email_assets/tinymce/js/tinymce/jquery.tinymce.min.js"></script>

<script>
  $('#prev_btn').click(function()
  {
    // alert("123");
    var headline = document.getElementById('pr_headline').value;
    var type = document.getElementById('pr_type').value;
    var content = tinymce.get('pr_content').getContent();
    var boiler_content = tinymce.get('pr_boiler_content').getContent();
    document.getElementById('preview_headline').innerHTML =headline;
    document.getElementById('preview_content').innerHTML =content;
    document.getElementById('preview_type').innerHTML =type;
    document.getElementById('preview_boiler_content').innerHTML =boiler_content;
    $('#previewPopup').modal('show'); 
  });
  $('#prev_btn_send').click(function()
  {
    var headline = document.getElementById('pr_headline').value;
    var type = document.getElementById('pr_type').value;
    var content = tinymce.get('pr_content').getContent();
    var boiler_content = tinymce.get('pr_boiler_content').getContent();
    document.getElementById('preview_headline').innerHTML =headline;
    document.getElementById('preview_content').innerHTML =content;
    document.getElementById('preview_type').innerHTML =type;
    document.getElementById('preview_boiler_content').innerHTML =boiler_content;
    $('#previewPopup').modal('show'); 
  });
</script>

<script>
tinymce.init({ 
selector:'textarea', 
branding: false,
image_description: false,
image_title: true,
height: 500,
default_link_target: "_blank",
media_live_embeds: true,
plugins: ["autolink lists image charmap print preview anchor","visualblocks code","insertdatetime table contextmenu paste imagetools", "wordcount", "media", "link"],
toolbar: 'undo redo | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist |  image media link | preview',
   
     // we override default upload handler to simulate successful upload
     images_upload_handler: function (blobInfo, success, failure) 
     {
       var xhr, formData;
       xhr = new XMLHttpRequest();
       xhr.withCredentials = false;
       xhr.open('POST', '/pressuser/image_upload');
       xhr.onload = function() {
         var json;
   
         if (xhr.status != 200) {
           failure('HTTP Error: ' + xhr.status);
           return;
         }
         json = JSON.parse(xhr.responseText);
   
         if (!json || typeof json.location != 'string') {
           failure('Invalid JSON: ' + xhr.responseText);
           return;
         }
         success(json.location);
       };
       formData = new FormData();
   
       if( typeof(blobInfo.blob().name) !== undefined )
           fileName = blobInfo.blob().name;
       else
           fileName = blobInfo.filename();
       formData.append('file', blobInfo.blob(), fileName);
       formData.append('_token', "{{ csrf_token() }}");
   
       // formData.append('file', blobInfo.blob(), fileName(blobInfo));
       xhr.send(formData);
   
       // setTimeout(function() 
       // {
       //   // no matter what you upload, we will turn it into TinyMCE logo (smiley)
       //   success('http://moxiecode.cachefly.net/tinymce/v9/images/logo.png');
       // }, 2000);
     },
   
     // init_instance_callback: function (ed) 
     // {
     //   ed.execCommand('mceImage');
     // }
   });
</script>

<script type="text/JavaScript">
    $('#pr_headline').on("input", function() {
      var dInput = this.value;
      console.log(dInput);
      $('#headline_pr').text(dInput);
    });
</script>

<script>
  $('#pop_recipient_btn').click(function()
  {
    var data = $('.recipient_form').serialize();
    action_load_link_to_modal('/pressuser/choose_recipient?'+data, 'md');
});
</script>

<script>
  $('#pop_chosen_recipient_btn').click(function()
  {
    $('#recipient_name').each(function()
    {
      alert($(this).text())
    });
  });
</script>

@endsection