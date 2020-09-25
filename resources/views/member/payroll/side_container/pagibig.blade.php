<form class="form-horizontal global-submit" role="form" action="/member/payroll/pagibig_formula/pagibig_formula_save" method="POST">
	<h4>PAGIBIG / HDMF</h4>
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="form-group">
		<div class="col-md-4">
			<small>Percentage</small>
			@if( $pagibig == null )
				<input type="number" name="payroll_pagibig_percent" class="form-control text-right" value="0">
			@else
				<input type="number" name="payroll_pagibig_percent" class="form-control text-right" value="{{$pagibig->payroll_pagibig_percent}}">
			@endif
		</div>
		<div class="col-md-4">
			<small>ER SHARE</small>
			@if( $pagibig == null )
				<input type="number" name="payroll_pagibig_er_share" class="form-control text-right" value="0">
			@else
				<input type="number" name="payroll_pagibig_er_share" class="form-control text-right" value="{{$pagibig->payroll_pagibig_er_share}}">
			@endif
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-8">
			<button class="btn btn-primary pull-right">Save</button>
		</div>
	</div>
</form>