<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		body
		{
			font-size: 13px;
			font-family: 'Titillium Web',sans-serif;
		}
	</style>
</head>
<body>
	<div class="form-group">
		<h2>Agent Transaction</h2>		
	</div>
<div class="form-group">
	<h4>
	<div class="col-md-6 text-left" style="float: left; width: 50%">
		<strong>NAME : </strong><br>
		<span>{{$agent->first_name." ".$agent->middle_name." ".$agent->last_name}}</span>
	</div>
	<div class="col-md-6 text-right" style="float: right; width: 50%">
		<div class="col-md-6 text-right" style="float: left; width: 50%">
			<strong>Source : </strong><br>
			<strong>Date : </strong><br>
		</div>
		<div class="col-md-6 text-left" style="float: left; width: 50%">
			<span>{{$sir_id == '' ? 'All SIR' : 'SIR #'.sprintf("%'.04d\n", $sir_id)}}</span><br>
			<span>{{$sdate}}</span><br>
		</div>
	</div>
	</h4>
</div>

@if($ctr_tr > 0)
<div>
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
            </tr>
        </thead>
        <tbody>
            @foreach($_transaction as $transaction)
	            <tr class="cursor-pointer">
	                <td>{{ $transaction['date'] }}</td>
	                <td>{{ $transaction['type'] }}</td>
	                <td>{{ $transaction['no'] }}</td>
	                <td>{{ $transaction['customer_name'] }}</td>
	                <td>{{ $transaction['due_date'] }}</td>
	                <td class="text-right">{{ currency("PHP",$transaction['balance']) }}</td>
	                <td class="text-right">{{ currency("PHP", $transaction['total']) }}</td>
	                <td class="text-center">
	               		<strong>{{$transaction["transaction_code"]}}</strong>
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
				There is no existing transaction for this SIR.
				</div>		
			</div>
		</div>
	</div>
</div>												
@endif
<div class="pull-right">
	<h4><strong>Receive Payment :</strong> {{$total}}</h4>
</div>
<br>
<div>
	@if(isset($rem_amount))
	<h4>Agent Remittance</h4>
	<span>{{currency("Php",$rem_amount)}}</span>
	<h4>Remittance Remarks</h4>
	<span>{!! $rem_remarks !!}</span>
	@endif
</div>
</body>
<style type="text/css">
	table
	{
		border-collapse: collapse;
		padding: 5px;
	}
	tr th
	{
		padding: 5px;
		border: 1px solid #000;
	}
	.watermark
	{
		font-size: 100px;
		text-align: center;
		 position:fixed;
		 left: 300px;
		 top: 250px;
		 opacity:0.5;
		 z-index:99;
		 color:#000;

		 -ms-transform: rotate(-40deg); /* IE 9 */
	    -webkit-transform: rotate(-40deg); /* Chrome, Safari, Opera */
	    transform: rotate(-40deg);
	}
</style>
</html>