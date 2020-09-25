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
           <h1 class="create_news"> Create News Release</h1>
           <div>
           <form method="post">
           <textarea class="mce"> </textarea>
         </form>
       </div>
        </div>     
    </div>
    </header>
</div>
<script src='https://cloud.tinymce.com/stable/tinymce.min.js'></script>
<script src="/email_assets/tinymce/js/tinymce/tinymce.min.js"></script>
<script src="/email_assets/tinymce/js/tinymce/tinymce.js"></script>
<script src="/email_assets/tinymce/js/tinymce/jquery.tinymce.min.js"></script>
<script type="text/javascript">
  tinymce.init({
  selector: 'textarea',  // change this value according to your HTML
  toolbar: "insertfile undo redo  styleselect  bold italic  alignleft aligncenter alignright alignjustify  bullist numlist outdent indentlink image",
  plugins: "advlist anchor autolink autoresize autosave bbcode charmap code codesample colorpicker compat3x contextmenu directionality emoticons fullpage fullscreen help hr image imagetools importcss insertdatetime legacyoutput link media nonbreaking hr image imagetools importcss insertdatetime legacyoutput link media nonbreaking noneditable pagebreak paste preview print save searchreplace spellchecker tabfocus table template textcolor textpattern toc visualblocks visualchars wordcount"
});

/*tinymce.init({
    selector: ".mce",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});*/
</script>
@endsection   

