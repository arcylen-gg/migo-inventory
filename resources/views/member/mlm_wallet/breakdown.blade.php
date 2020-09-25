<div class="col-md-12">
{!! $customer_view !!}
</div>
<div class="col-md-12">
	<hr>
</div>
<div class="col-md-12">
<div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Income Summary</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
		<table class="table table-condensed table-bordered" style="overflow-y: auto;">
			<thead>
			<th>START BALANCE</th>
			<th>COMPLAN</th>
			<th>AMOUNT</th>
			<th>NEW BALANCE</th>
			</thead>
			<tbody>
			<?php $last_amount = 0; ?>
			<?php $last_date = Carbon\Carbon::now(); ?>
			@foreach($sort_by_date as $key => $value)

				<tr style="border-top: 3px solid #d2d6de;">
					<td colspan="40">{{$key}}</td>
				</tr>
				@foreach($value as $key2 => $value2)
				
				<tr>
					<td>{{currency('PHP', $last_amount)}}</td>
					<td>{{$value2->wallet_log_plan}}</td>
					<td>{{currency('PHP', $value2->wallet_log_amount)}}</td>
					<?php $last_amount += $value2->wallet_log_amount; ?>
					<td>{{currency('PHP', $last_amount)}}</td>
				</tr>
				@endforeach
				<?php $last_date = $key; ?>
				<tr>
					<td colspan="40">
						<span class="pull-right"><h4>{{$last_date}}  End Balance: {{currency('PHP', $last_amount)}}</h4></span>
					</td>
				</tr>
			@endforeach	
			
			</tbody>
		</table>
	</div>
</div>    
</div>