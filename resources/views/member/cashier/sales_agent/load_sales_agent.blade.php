@foreach($_agent as $agent)
<option commission-percent="{{$agent->commission_percent}}" value="{{$agent->employee_id}}">{{ucwords($agent->first_name.' '.$agent->middle_name.' '.$agent->last_name)}}</option>
@endforeach