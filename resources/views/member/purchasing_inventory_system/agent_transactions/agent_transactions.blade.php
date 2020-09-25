@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-users"></i>
            <h1>
                <span class="page-title">{{$agent->first_name." ".$agent->middle_name." ".$agent->last_name}}</span>
                <input type="hidden" name="customer_id" class="agent-id" value="{{$agent->customer_id}}" />
                <small>
                Agent Details
                </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
    	<div class="row">
	    	<div class="form-group load-customer-detail col-md-6">
	    		<div class="customer-detail-container">
					<h4>Full Name : {{$agent->first_name." ".$agent->middle_name." ".$agent->last_name}}</h4>
					<h4>Email : {{$agent->email}}</h4>
					<h4>Username : {{$agent->username}}</h4>
					<h4>Position : {{$agent->position_name}}</h4>
				</div>
	    	</div>
	    	<div class="form-group col-md-6">
	    		<div class="col-md-12">
	    			<a link="/member/pis/agent/edit/{{$agent->employee_id}}" size="md" class="panel-buttons btn btn-custom-primary pull-right popup">Edit Agent</a>
	    		</div>
	    		<!-- <div clas="col-md-12">
		    		<div class="pull-right">
		    			<h3> BALANCE <span class="green">{{currency("PHP", isset($customer->balance) or 0)}}</span></h3>
		    		</div>
	    		</div> -->
	    	</div>
	    </div>
    </div>
</div>
<div class="row clearfix" style="margin-bottom: 15px;">
    <form method="get" class="range-date">
        <div class="col-sm-3">
        	<select name="sir_id" class="form-control datepicker">
      			<option value="">All SIR</option>
        		@foreach($_sir_id as $sir)
        			<option value="{{$sir->sir_id}}">SIR NO: {{$sir->sir_id}}</option>
        		@endforeach
        	</select>
        </div>
        <div class="col-sm-3">
        <a href="/member/pis/agent_transaction/print/{{$agent->employee_id}}?sir_id={{Request::input('sir_id')}}" class="btn btn-primary form-control" target="_blank">Print Transaction Below</a>
        </div>
    </form>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
    	@if(count($__transaction) > 0)
	    	<div class="table-responsive">
			    <table class="table table-hover table-bordered table-striped table-condensed">
			        <thead>
			            <tr>
			                <!-- <td class="col-md-2"></td> -->
			                <th>ID</th>
			                <th>Type</th>
			                <th>No</th>
			                <th>Customer Name</th>
			                <th>Due Date</th>
			                <th>Balance</th>
			                <th>Total</th>
			                <th>Status</th>
			                <th>Transaction Code</th>
			                <th>Action</th>
			            </tr>
			        </thead>
			        <tbody>
			            @foreach($__transaction as $transaction)
				            <tr class="cursor-pointer" onClick="window.location='/member/customer/{{$transaction['reference_name']}}?id={{$transaction['no']}}'">
				                <td>{{ $transaction['date'] }}</td>
				                <td>{{ $transaction['type'] }}</td>
				                <td>{{ $transaction['no'] }}</td>
				                <td>{{ $transaction['customer_name'] }}</td>
				                <td>{{ $transaction['due_date'] }}</td>
				                <td>{{ currency("PHP",$transaction['balance']) }}</td>
				                <td>{{ currency("PHP", $transaction['total']) }}</td>
				                <td>
                                    @if($transaction['reference_name'] == "receive_payment")
                                    <a class="btn btn-default form-control">Closed</a>
				                	@elseif($transaction['status'] == 0 && $transaction['reference_name'] == "invoice")   	
                                    <a class="btn btn-warning form-control">Open</a>
				                	@elseif($transaction['reference_name'] == "invoice" && $transaction['status'] == 1)
                                    <a class="btn form-control" style="background-color: #78C500;color: #fff">Paid</a>
				                	@endif
				                </td>
				                <td class="text-center">
				               		<strong>{{$transaction["transaction_code"]}}</strong>
				                </td>
				                <td>
				                    <!-- ACTION BUTTON -->
				                   @if($transaction['reference_name'] == "invoice" && $transaction['status'] == 1)
			                       <a target="_blank" href="/member/customer/customer_invoice_pdf/{{$transaction['no']}}">View Invoice</a>
			                       @endif
				                </td>
				            </tr>
			            @endforeach
			        </tbody>
			    </table>
			</div>
		@else
			<div class="container text-center">
				<div class="row vcenter">
					<div class="col-md-12">
						<div class="error-template">
							<h2 class="message">No Trasaction Found</h2>
							<div class="error-details">
							There is no existing transaction for this agent.
							</div>		
						</div>
					</div>
				</div>
			</div>												
        @endif
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript">
$('body').on("change", ".datepicker", function()
{
   $('.range-date').submit();
});	
</script>
<script type="text/javascript" src="/assets/member/js/customer_detail.js"></script>
@endsection