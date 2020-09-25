@extends('mlm.layout')
@section('content')
<div style="padding: 15px;" class="panel panel-default panel-block">
	<div>
		<img style="width: 100%;" src="{{ $post->post_image }}">
	</div>
	<div style="margin-top: 20px; font-size: 20px; font-weight: 700;">{{ $post->post_title }}</div>
	<div class="clearfix">{!! $post->post_content !!}</div>
	<div class="clearfix text-right">
		<a href="/mlm">&laquo; Back</a>
	</div>
</div>
@endsection