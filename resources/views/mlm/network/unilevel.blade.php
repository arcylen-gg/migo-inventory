@extends('mlm.layout')
@section('content')
<?php 
$data['title'] = 'Network List Unilevel';
$data['sub'] = '';
$data['icon'] = 'fa fa-retweet';
?>  
@include('mlm.header.index', $data) 
<center>
  {!! $tree_level->appends(Request::capture()->except('page'))->render() !!}
</center>
<div class="col-md-12">
	<ul class="timeline ">
	@foreach($tree as $key => $value)
		<li class="time-label">
              <span class="bg-aqua">
                LEVEL {{$key}}
              </span>
        </li>
        @foreach($value as $key => $value)
        <li>
        	
          <div class="timeline-item clearfix">
            <h3 class="timeline-header"><a href="javascript:">{{name_format_from_customer_info($value)}}</a> </h3>
            <div class="timeline-body ">
                  	<span style="color: gray;">Slot: </span> {{$value->slot_no}}
                  	<br>
                  	<span style="color: gray;">Membership: </span> {{$value->membership_name}}
                  	<br>
                  	<span style="color: gray;">Membership Code: </span> {{$value->membership_activation_code}}
                  	<br>
            </div>
            <div class="timeline-footer">

            </div>
          </div>
        </li>
        @endforeach

	@endforeach
	</ul>
  <center>{!! $tree_p->appends(Request::capture()->except('tree'))->render() !!}</center>
</div>  
@endsection
@section('script')

@endsection