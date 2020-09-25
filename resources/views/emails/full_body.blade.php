@if(isset($template))
    @include('emails.header', $template)
@endif
@if(isset($body))
	<hr>
	{!! $body !!}
	<hr>
@endif
@if(isset($template))
	<br>
    @include('emails.footer', $template)
@endif