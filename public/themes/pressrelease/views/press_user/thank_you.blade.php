@extends("layout")
@section("content")
<div class="main-wrapper">
    <div class="container">
    	<div class="border-container">
    		<i class="fa fa-check-circle-o" aria-hidden="true"></i>
    		<div class="title-container">Thank You</div>
    		<div class="description-container">Your account is successfully registered</div>
    		<div class="button-container"><a href="/signin">Proceed to login &raquo;</a></div>
    	</div>
    </div>
</div>

@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/thank_you.css">
@endsection

@section("script")

<script type="text/javascript">

</script>

@endsection