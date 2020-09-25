@extends("press_admin.admin")
@section("pressview")
<div class="background-container">
    <div class="pressview">
        <div class="press-container">
              
            <div class="title-container">Email's Press Release</div>
            @foreach($edit as $edits)
            <form method="post">
              {{csrf_field()}}
            <div class="title">Title:</div>
            <input type="text" name="pr_headline" value="{{$edits->pr_headline}}"><br>
            <div class="title">Release Text Body:</div>
            <textarea name="pr_content">{!!$edits->pr_content!!}</textarea><br>
            <div class="title">Boilerplate:</div>
            <textarea name="pr_boiler_content">{!!$edits->pr_boiler_content!!}</textarea>
            <div class="button-container">
                <button type="submit" name="save" value="save" formaction="/pressadmin/email_save">Save as draft</button>
            </div>
            
            </form>
            @endforeach

            
        </div>
    </div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/press_admin_email_edit.css">
@endsection

@section("script")

<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script src="/email_assets/tinymce/js/tinymce/tinymce.min.js"></script>
<script src="/email_assets/tinymce/js/tinymce/tinymce.js"></script>
<script src="/email_assets/tinymce/js/tinymce/jquery.tinymce.min.js"></script>

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

@endsection