@extends('mlm.layout')
@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class=" fa fa-address-card "></i></span>
        <div class="info-box-content">
                      <span class="info-box-text"><h2> Used Product Codes </h2></span> 
          <span class="info-box-text" style="color: gray;"><small> All used product codes are shown here. </small></span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box">
		    <div class="box-header with-border">
		      <!-- <h3 class="box-title">Used Product Codes</h3> -->
	   		</div>
   		
   		<div class="box-body">
	        <table class="table table-bordered">
		        <thead>
		            <th>Product Code</th>
		            <th>Product Name</th>
		            <th>Date Used</th>
		            <th>Rank Points</th>
		        </thead>
	        	<tbody>
		        	@if(count($_report) != 0)
		                @foreach($_report as $key => $report)
		                <tr>
			                <td>{{$report->item_activation_code}}</td>
			                <td>{{$report->item_name}}</td>
			                <td>{{$report->date_used}}</td>
			                <td>{{$report->STAIRSTEP}}</td>
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