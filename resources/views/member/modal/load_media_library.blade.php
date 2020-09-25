<!-- @for($i=1;$i<5;$i++)
    <div class="image-container">
        <div class="check-logo"><i class="fa fa-check" aria-hidden="true"></i></div>
        <img src="/uploads/image{{$i}}.jpg">
    </div>
@endfor -->

@if(isset($_image))
	@foreach($_image as $image)
		@if($image)
		<div class="image-container" img-id="{{$image->image_id}}">
	        <div class="check-logo"><i class="fa fa-check" aria-hidden="true"></i></div>
	        <img src="{{$image->image_path}}">
	    </div>
		@endif
	@endforeach
@endif