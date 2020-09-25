@extends('member.layout')
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/external/selectize.js/dist/css/selectize.default.css">
<link rel="stylesheet" href="/assets/member/css/customBTN.css" type="text/css" />
@endsection

@section('content')
<form class="form-horizontal" action="/member/about/storeDescription" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	 <div class="form-box-divider">
        <div class="fieldset">
            
            <label>Title header</label>
            <div><input name="title_header" value="" placeholder="Title header" type="text" class="form-control"></div>
        </div>
        <br>
        <div class="fieldset">

            <label>Description</label>
            <div>
                <textarea name="description" class="form-control tinymce"></textarea>
            </div>
        </div>
        <Br>
        <div class="fieldset">
        	<button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </div>
</form>
<hr>
<div class="form-horizontal" style="margin-bottom:50px;">
	<h2><u>Description Details</u></h3>
	@foreach($_about as $about)
	<div class="form-group">
		<div class="col-md-12">
			<h4>{{$about->title}}&nbsp;<small><a href="/member/about/edit/{{Crypt::encrypt($about->about_us_id)}}"><i class="fa fa-pencil"></i>&nbsp;edit</a>&nbsp;</small><small class="color-red"><a href="#" data-content="{{Crypt::encrypt($about->about_us_id)}}" class="a-remove"><i class="fa fa-times"></i>&nbsp;Remove</a></small></h4>
			
			<br>
			<span class="">
			{!!$about->content!!}
			</span>
		</div>
	</div>
	<hr>
	@endforeach
</div>
@endsection

@section('script')
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'.tinymce',menubar:false,height:200, content_css : "/assets/member/css/tinymce.css"});</script>
<script type="text/javascript" src="/assets/member/js/mystore.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".a-remove").click(function(){
            var con = confirm("Are you sure you want to remove this information?");
            if(con){
                window.location = '/member/about/remove/'+$(this).data('content');
            }
        });
    });
</script>
@endsection