@extends('member.layout')
@section('content')
<div class="container">
  <h2>Analytics</h2>
  <p>Today</p>            
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Sent</th>
        <th>hard bounces</th>
        <th>Soft bounces</th>
        <th>Clicks</th>
        <th>Opens</th>
		<th>unique opens</th>
      </tr>
    </thead>
    <tbody>

      <tr>
        <td>{{$_array1->sent}}</td>
  		<td>{{$_array1->hard_bounces}}</td>
  		<td>{{$_array1->soft_bounces}}</td>
  		<td>{{$_array1->clicks}}</td>
  		<td>{{$_array1->opens}}</td>
  		<td>{{$_array1->unique_opens}}</td>
      </tr>  
	
    </tbody>
  </table>
</div>
@endsection

