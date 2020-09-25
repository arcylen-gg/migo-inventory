@extends('member.layout')
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/external/selectize.js/dist/css/selectize.default.css">
<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
@endsection

@section('content')
<form class="form-horizontal" action="/member/about/update" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	 <div class="form-box-divider">
        <div class="fieldset">
            
            <label>Title header</label>
            <div><input name="title_header" value="{{$about->title}}" placeholder="Title header" type="text" class="form-control"></div>
        </div>
        <br>
        <div class="fieldset">

            <label>Description</label>
            <div>
                <textarea name="description" class="form-control tinymce">{!!$about->content!!}</textarea>
            </div>
        </div>
        <Br>
        <div class="fieldset">
        	<button class="btn btn-primary" type="submit">Update</button>
        </div>
    </div>
</form>

@endsection

@section('script')
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'.tinymce',menubar:false,height:200, content_css : "/assets/member/css/tinymce.css"});</script>
<script type="text/javascript" src="/assets/member/js/mystore.js"></script>
@endsection