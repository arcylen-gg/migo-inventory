@extends('member.layout')
@section('css')
@endsection
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
	<div class="panel-heading">
		<div>
			<i class="fa fa-calendar"></i>
			<h1>
			<span class="page-title">Leave Scheduling</span>
			<small>
			Manage Leaves
			</small>
			</h1>
			<button class="btn btn-custom-primary panel-buttons pull-right popup" link="/member/payroll/leave_schedule/modal_create_leave_schedule">Create Schedule</button>
			<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
		</div>
	</div>
</div>
<div class="panel panel-default panel-block">
	<div class="panel body">
		<div class="leave-container">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#upcoming-leave">Upcoming Leave</a></li>
				<li><a data-toggle="tab" href="#used-leave">Used Leave</a></li>
			</ul>
			<div class="tab-content padding-5">
				<div id="upcoming-leave" class="tab-pane fade in active">
					@foreach($_upcoming as $key => $upcoming)
					<div class="custom-panel">
	       				<div class="custom-panel-header cursor-pointer">
	       					{{date('F d, Y', strtotime($key)).' '.$upcoming[0]['payroll_leave_temp_name']}}
	       				</div>
	       				<div class="width-100 display-table">
				          <div class="triangle-top-right"></div>
				          <div class="custom-panel-body">
				            <div class="custom-panel-child display-none">
				              <div class="process-container">
				              	<ul class="list-group">
				              	 @foreach($upcoming as $tag)
								  <li class="list-group-item padding-tb-10">{{$tag['payroll_employee_title_name'].' '.$tag['payroll_employee_first_name'].' '.$tag['payroll_employee_middle_name'].' '.$tag['payroll_employee_last_name'].' '.$tag['payroll_employee_suffix_name']}}
								  	<a href="#" class="pull-right popup" link="/member/payroll/leave_schedule/delete_confirm_schedule_leave/{{$tag['payroll_leave_schedule_id']}}" size="sm"><i class="fa fa-times" aria-hidden="true"></i></a>
								  	<span class="pull-right margin-right-20">{{date('h:i', strtotime($tag['leave_hours']))}}</span>
								  </li>
								 @endforeach
								</ul>
				              </div>
				            </div>
				          </div>
				        </div>
	       			</div>
					@endforeach
				</div>
				<div id="used-leave" class="tab-pane fade">
					@foreach($_used as $key => $used)
					<div class="custom-panel">
	       				<div class="custom-panel-header cursor-pointer">
	       					{{date('F d, Y', strtotime($key))}}
	       				</div>
	       				<div class="width-100 display-table">
				          <div class="triangle-top-right"></div>
				          <div class="custom-panel-body">
				            <div class="custom-panel-child display-none">
				              <div class="process-container">
				              	<ul class="list-group">
				              	 @foreach($used as $tag)
								  <li class="list-group-item padding-tb-10">{{$tag['payroll_employee_title_name'].' '.$tag['payroll_employee_first_name'].' '.$tag['payroll_employee_middle_name'].' '.$tag['payroll_employee_last_name'].' '.$tag['payroll_employee_suffix_name']}}<a href="#" class="pull-right popup" link="/member/payroll/leave_schedule/delete_confirm_schedule_leave/{{$tag['payroll_leave_schedule_id']}}" size="sm"><i class="fa fa-times" aria-hidden="true"></i></a>
								  <span class="pull-right margin-right-20">{{date('h:i', strtotime($tag['leave_hours']))}}</span>
								  <span class="pull-right margin-right-20 center">{{$tag['payroll_leave_temp_name']}}</span>
								  </li>
								 @endforeach
								</ul>
				              </div>
				            </div>
				          </div>
				        </div>
	       			</div>
					@endforeach
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
	toggle_custom_panel_header_event();
	function toggle_custom_panel_header_event()
	{
		$(".custom-panel-header").unbind("click");
		$(".custom-panel-header").bind("click", function()
		{
			var child = $(this).parents(".custom-panel").find(".custom-panel-child");
			child.slideToggle();
		});
	}

	function reload_container()
	{
		$(".leave-container").load("/member/payroll/leave_schedule .leave-container", function()
		{
			toggle_custom_panel_header_event();
		});
	}

	/* CALL A FUNCTION BY NAME */
	function executeFunctionByName(functionName, context /*, args */) {
	  var args = [].slice.call(arguments).splice(2);
	  var namespaces = functionName.split(".");
	  var func = namespaces.pop();
	  for(var i = 0; i < namespaces.length; i++) {
	    context = context[namespaces[i]];
	  }
	  return context[func].apply(context, args);
	}

	function submit_done(data)
	{
		try
		{
			data = JSON.parse(data);
		}
		catch(error){}

		data.element.modal("toggle");
		reload_container();
		executeFunctionByName(data.function_name, window);
	}
</script>
@endsection