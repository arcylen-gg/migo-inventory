@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
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
        <li class=""><a href="/member/page/press_release_email/create_press_release"><big class="big">Create</big><small class="small"> New Release</small></a></li>
        <li class="choose"><a href="/member/page/press_release_email/choose_recipient_press_release"><big class="big">Choose</big><small class="small"> recipients</small></a></li>
        <li class="active"><a href="/member/page/press_release_email/view_send_email_press_release"><big class="big">Send</big><small class="small"> Release </small></a></li>
      </ul>     
    </div>
    </div>
  </header>
</head>
<div class="box-body">
  <button class="input_chose_email btn btn-primary"> Email List </button>
  <input type="hidden"  class="_token1" id="_token1" value="{{csrf_token()}}"/>
  <form  name="myform" method ="POST" action = "/member/page/press_release_email/send_press_release" class="form-horizontal" id="get_data_tinymce1">
    {{csrf_field()}}
      <div class="subject">
      <div class="col-lg-6">
        <input type="hidden" id="input_subject" placeholder="To" class="form-control email-to-container subject_email" name="to" value="{{$mails}}">
      </div>
    </div>
    <br>
    <div class="subject">
      <div class="col-lg-6">
        <input type="text" placeholder="From" id="input_subject" class="form-control subject_email" name="from">
      </div>
    </div>
    <br>
      <div class="subject">
      <div class="col-lg-6">
        <input type="text" placeholder="subject" id="input_subject" class="form-control subject_email email-subject-container" name="subject">
      </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <div class="textarea_container">
    <div class="tiny">
      <textarea class="mce tiny email-content-container" id="texteditor" style="margin-left: 10px !important; margin-right: 10px !important; margin-top: -10px !important;">
      </textarea>
    </div>
    </div>
    <button class="btn btn-primary continue" type ="submit"> send </button>
    <button class="btn btn-primary preview"> preview </button>
  </form>
</div>
<div class="modal fade" id="email_databaseModal" tabindex="-1" role="dialog" aria-labelledby="email_database_ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal_body">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <table class="table table-inbox table-hover table_email_list" id="table_email_list" style="table-layout: fixed;">
            <tbody>
              @foreach($_email_list as $email_list)
              <tr class="unread" email_id="{{$email_list->email_id}}">
                <td class="view-message dont-show" value="{{$email_list->email_id}}" email-id="{{$email_list->email_id}}">
                  <a>{{$email_list->email_title}}</a>
                  <!-- EXTRA LALAGYANAN PARA MAPASA MO YUNG DATA -->
                  <input type="hidden" class="email-subject-{{$email_list->email_id}}" value="{{$email_list->email_title}}">
                </td>
                <td class="view-message" style="white-space: nowrap; display: none;">{!!$email_list->email_content!!}</td>
                <td class="view-message  text-right">{{$email_list->email_time}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script>
$(".input_chose_email").click(function() {
$('#email_databaseModal').modal('show');

});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script src="/email_assets/tinymce/js/tinymce/tinymce.min.js"></script>
<script src="/email_assets/tinymce/js/tinymce/tinymce.js"></script>
<script src="/email_assets/tinymce/js/tinymce/jquery.tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
selector: '.mce',  // change this value according to your HTML
theme:'modern',
menubar:false,
branding:false,
plugins: "autoresize preview",
toolbar: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
toolbar2: "| link unlink anchor | image media | forecolor backcolor | caption",
});
function submit_selected_image_done(data) {
var image_path = data.image_data[0].image_path;
tinyMCE.execCommand('mceInsertContent',false,'<img src="'+image_path+'"/>');
}
</script>
<script type="text/javascript">
  $('.preview').click(function(){
    tinyMCE.activeEditor.execCommand('mcePreview');
  });
</script>
<script type="text/javascript" src="/email_assets/js/create_press_release.js"></script>
<script type="text/javascript" src ="http://malsup.github.com/jquery.form.js"></script>
<script>
function addRowHandlers(email_id) {
var rows = document.getElementById("table_email_list").rows;
for (i = 0; i < rows.length; i++) {
rows[i].onclick = function (){ return function(){
var email_id= $(this).attr("email_id");
pass_data(email_id);
var title = this.cells[0].innerHTML;
var content = this.cells[1].innerHTML;
var time = this.cells[2].innerHTML;
tinymce.get('texteditor').setContent(content);

/*function pass_data(email_id)  
{
  $('.email-subject-container').val($('.email-subject-'+email_id).val());
$('#email_databaseModal').modal('hide');
}*/
};}(rows[i]);

}
}
window.onload = addRowHandlers();
// eto Yung Onclick na nasa TD pag-kinclick yun dto pupunta
function pass_data(email_id)
{
$('.email-subject-container').val($('.email-subject-'+email_id).val());
  
  /*$('.email-to-container').val($('.email-content-'+email_id).val());*/
$('#email_databaseModal').modal('hide');
}
</script>
@endsection