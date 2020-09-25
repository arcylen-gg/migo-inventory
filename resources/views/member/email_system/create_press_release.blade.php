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
    <div class="panel-body form-horizontal tab_header">
        <div class="form-group">
           <ul class="nav nav-pills">
		    <li class="active"><a href="/member/page/press_release_email/create_press_release"><big class="big">Create</big><small class="small"> New Release</small></a></li>
		    <li class="choose"><a href="/member/page/press_release_email/choose_recipient_press_release"><big class="big">Choose</big><small class="small"> recipients</small></a></li>
		    <li><a href="/member/page/press_release_email/view_send_email_press_release"><big class="big">Send</big><small class="small"> Release </small></a></li>
		  </ul>     
    </div>
    </div>
    </header>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<div class="box-body">
 <button type= "button" class="image-gallery btn btn-primary gallery" key="1234"> Image Gallery </button>
<input type="hidden"  class="_token1" id="_token1" value="{{csrf_token()}}"/>
                                          <form  method ="post" action ="/member/page/press_release_email/save_email_press_release" role="form" class="form-horizontal" id="get_data_tinymce">
                                            {{csrf_field()}}
                                              	<div class="subject">
                                                  <div class="col-lg-6 form-group form-inline">
                                                    <label style="margin-left: 10px;">Subject:  </label>
                                                      <input type="text" placeholder="subject" id="input_subject" class="form-control subject_email" name="subject">
                                                  </div> 
                                              </div>
                                              <br>
                                              <br>
                                              <div class="textarea_container">
                                              <div class="tiny">
                                                <textarea class="mce tiny" id="texteditor" style="margin-left: 10px !important; margin-right: 10px !important;">                
                                                </textarea>
                                                <input type="submit" class="btn btn-primary save" value="Save">
                                            </div>
                                            </div>
                                          </form>  
                                          <button class="btn-primary btn_preview_create_press_release btn btn-primary"> preview </button>
                                           <button class="btn-primary create_press_releas_btn_continue btn btn-primary"><a href="/member/page/press_release_email/choose_recipient_press_release" class ="btn_continue" style="color:white !important;"> continue </a></button>
</div>
</div>

<script src="/email_assets/tinymce/js/tinymce/tinymce.min.js"></script>
<script src="/email_assets/tinymce/js/tinymce/tinymce.js"></script>
<script src="/email_assets/tinymce/js/tinymce/jquery.tinymce.min.js"></script>
<script type="text/javascript">
  tinymce.init({
  selector: '.mce',  // change this value according to your HTML
  theme:'modern',
  menubar:false,
  branding:false,
  /*width : "640",*/  
  plugins: "autoresize preview",
  toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
  toolbar2: "| link unlink anchor | image media | forecolor backcolor | print preview code | caption",
    
});

  function submit_selected_image_done(data) {

    var image_path = data.image_data[0].image_path;


    tinyMCE.execCommand('mceInsertContent',false,'<img src="'+image_path+'"/>');

}
</script>
<script type="text/javascript">
	$('.btn_preview_create_press_release').click(function(){
		 tinyMCE.activeEditor.execCommand('mcePreview');
	});
</script>
<script type="text/javascript" src="/email_assets/js/create_press_release.js"></script>
<script type="text/javascript" src ="http://malsup.github.com/jquery.form.js"></script>
@endsection