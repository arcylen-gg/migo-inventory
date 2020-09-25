@extends('mlm.layout')
@section('content')
{!! $header !!}
@include('mlm.report.global')

 <div class="box box-primary">
  <div class="box-body">
  	<center>Points Entry Log</center>
    <ul class="products-list product-list-in-box">
      @if(isset($points_report))
        @foreach($points_report as $key => $value)
          <li class="item">
            <div class="product-img">
              {!! mlm_profile($value) !!}
            </div>
            <div class="product-info">
              <a href="javascript:void(0)" class="product-title">Sponsor: {{name_format_from_customer_info($value)}}
                  <span class="product-description" style="color: black;">
					Slot : {{$value->slot_no}}
					<table class="table table-condensed table-bordered">
						<thead>
							<th>Membership Points</th>
							<th>Points Limit</th>
							<th>Points Flushed</th>
							<th>Start Left</th>
							<th>Start Right</th>
							<th>Earned Left</th>
							<th>Earned Right</th>
							<th>End Left</th>
							<th>End Right</th>


							<tr>
								<td>{{$value->binary_report_point_membership}}</td>
								<td>{{$value->binary_report_point_limit}}</td>
								<td>{{$value->binary_report_point_deduction }}</td>
								<td>{{$value->binary_report_s_left}}</td>
								<td>{{$value->binary_report_s_right}}</td>
								<td>{{$value->binary_report_s_points_l}}</td>
								<td>{{$value->binary_report_s_points_r}}</td>
								<td>{{$value->binary_report_e_left}}</td>
								<td>{{$value->binary_report_e_right}}</td>
							</tr>
						</thead>
					</table>
                  </span>
            </div>
          </li>
        @endforeach  
    @endif    
    </ul>
  </div>
  <div class="box-footer text-center">
  </div>
</div>


@endsection