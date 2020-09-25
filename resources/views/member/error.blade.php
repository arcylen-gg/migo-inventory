@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading text-center" style="padding: 100px 0;">
		<div class="title" style="font-size: 20px;">{!! $title !!}</div>
		<div class="content" style="font-size: 14px;">{!! $content !!}</div>
	</div>
</div>

@endsection

