@extends("member.member_layout")
@section("member_content")
<div class="report-container">
	<div class="report-header clearfix">
		<div class="left">
			<div class="icon">
				<h1><i class="fa fa-recycle"></i></h1>
			</div>
			<div class="text">
				<div class="name">{{$page}}</div>
				<div class="sub">Submit button to earn wallet</div>
			</div>
		</div>
	</div>
	<div class="form-group">
        <div class="col-md-12">
            <label for="basic-input">Slot</label>
            <select name="slot" class="col-md-4 slot-owner">
                @foreach($slot as $s)
                <option class="form-control" value="{{$s->slot_no}}">{{$s->slot_no}}</option>
                @endforeach
            </select>
        </div>
    </div><br>
	<div class="report-content">
		<center>
			<form method="post" action="/members/submitcaptcha">
				{{ csrf_field() }}
				<input type="hidden" class="hidden_slot_no" name="slot_no">
				<div class="holder">
				  	<div class="g-recaptcha" data-sitekey="6Let6UAUAAAAAD0MvJH0Tl_Bej1YkE1oaD0mIE-j"></div>
				  	<div style="margin-top: 10px;">
				  		<button class="btn btn-primary btn-custom-primary submit-captcha" type="submit">Submit</button>
				  		<span class="count-holder"></span>
				  	</div>
				</div>
			</form>	
		</center>
	</div>
</div>
@endsection
@section("member_script")
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="/themes/unitywealth/js/recaptcha.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
	@if(Session::get("response")=='success')
    toastr.success("Success");
    @elseif(Session::get("response")=='error')
    toastr.error("Error");
    @elseif(Session::get("response")=='no_points')
    toastr.error("No More Wallet Available");
    @endif

    @if(Session::get("response"))
    $(".submit-captcha").attr("disabled", true);
    $(".submit-captcha").prop("disabled", true);

	var counter = 21;
	var myInterval = setInterval(function () 
	{
		--counter;
		// to stop the counter
		
		$(".count-holder").text(counter);

		if (counter == 0) 
		{
			clearInterval(myInterval);
			$(".count-holder").remove();
			$(".submit-captcha").removeAttr("disabled");
    		$(".submit-captcha").removeProp("disabled");
		}
	}, 1000);

    @endif
</script>
@endsection
@section("member_css")
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/report.css">
@endsection