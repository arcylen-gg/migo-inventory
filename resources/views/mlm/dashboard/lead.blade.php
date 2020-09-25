@extends('mlm.layout')
@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class=" fa fa-address-card "></i></span>
        <div class="info-box-content">
                      <span class="info-box-text"><h2> Leads </h2></span> 
          <span class="info-box-text" style="color: gray;"><small> All customer leads are shown here. </small></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box">
		    <div class="box-header with-border">
		      <h3 class="box-title">Lead List</h3>
	   		</div>
   		
   		<div class="box-body">
	        <table class="table table-bordered">
		        <thead>
		            <th>Name</th>
		            <th>Email</th>
		            <th>Phone Number</th>
		            <th>Mobile Number</th>
		            <th>Lead Used</th>
		        </thead>
	        	<tbody>
		        	@if(count($leads) != 0)
		                @foreach($leads as $key => $lead)
		                <tr>
			                <td>{{$lead->title_name}} {{$lead->first_name}} {{$lead->middle_name}} {{$lead->last_name}} {{$lead->suffix_name}}</td>
			                <td>{{$lead->email}}</td>
			                <td>{{$lead->customer_phone}}</td>
			                <td>{{$lead->customer_mobile}}</td>
			                <td>{{$lead->lead_used != 0 ? "Used" : "Not Used"}}</td>
		                </tr>
		                @endforeach
	           		@else
			            <tr>
			                <td colspan="40"><center>---No Record Found---</center></td>
			            </tr>
	                @endif
	        	</tbody>
      		</table>
    	    </div>
    	</div>
   	</div>
@endsection
@section('script')
@endsection