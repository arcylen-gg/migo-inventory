<form class="form-horizontal global-submit" role="form" action="/member/payroll/sss_table_list/sss_table_save" method="POST">
	<h4>SSS Table</h4>
	<input type="hidden" name="_token" value="{{csrf_token()}}">

	<div class="load-data" target="value-id-1">
		<div id="value-id-1">
			<table class="table table-bordered table-condensed ">
				<tr>
					<td colspan="2">
						Range of Compensation
					</td>
					<td>
						Monthly Salary Credit
					</td>
					<td>
						ER
					</td>
					<td>
						EE
					</td>
					<td>
						Total
					</td>
					<td>
						EC/ER
					</td>
					<td width="5%"></td>
				</tr>

				<tbody class="tbl-sss">
				@foreach($_sss as $sss)
					<tr>
						<td>
							<input type="number" step="any" name="payroll_sss_min[]" class="border-none width-100 text-right" value="{{$sss->payroll_sss_min}}">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_max[]" class="border-none width-100 text-right" value="{{$sss->payroll_sss_max}}">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_monthly_salary[]" class="border-none width-100 text-right" value="{{$sss->payroll_sss_monthly_salary}}">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_er[]" class="border-none width-100 text-right" value="{{$sss->payroll_sss_er}}">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_ee[]" class="border-none width-100 text-right" value="{{$sss->payroll_sss_ee}}">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_total[]" class="border-none width-100 text-right" value="{{$sss->payroll_sss_total}}">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_eec[]" class="border-none width-100 text-right" value="{{$sss->payroll_sss_eec}}">
						</td>
						<td class="text-center" valign="cener">
							<a href="#" class="remove-tr"><i class="fa fa-trash-o"></i></a>
						</td>
					</tr>
				@endforeach
				</tbody>
				
				<tbody class="display-none tbl-ref">
					<tr>
						<td>
							<input type="number" step="any" name="payroll_sss_min[]" class="border-none width-100 text-right">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_max[]" class="border-none width-100 text-right">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_monthly_salary[]" class="border-none width-100 text-right">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_er[]" class="border-none width-100 text-right">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_ee[]" class="border-none width-100 text-right">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_total[]" class="border-none width-100 text-right">
						</td>
						<td>
							<input type="number" step="any" name="payroll_sss_eec[]" class="border-none width-100 text-right">
						</td>
						<td class="text-center" valign="cener">
							<a href="#" class="remove-tr"><i class="fa fa-trash-o"></i></a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<i><span class="color-red">*</span><span class="">Note: Zero (0) value in the last row indicates greater than other provided value.</span></i>
			<button class="btn btn-primary pull-right">Save</button>
		</div>
	</div>
</form>
<script type="text/javascript">
	var tbl_add = new tbl_add();
	function tbl_add() 
	{
		init();
		function init()
		{
			append_tr();
			remove_tr();
		}
		function append_tr()
		{
			$(".tbl-sss tr").unbind("click");
			$(".tbl-sss tr:last").bind("click", function()
			{

				$(".tbl-sss tr:last").unbind("click");
				var ref = $(".tbl-ref").html();
				$(".tbl-sss").append(ref);
				append_tr();
				remove_tr();
			});
		}
		function remove_tr()
		{
			$(".remove-tr").unbind("click");
			$(".remove-tr").bind("click", function(){
				$(this).parents("tr").remove();
				append_tr();
			});
		}
	}
</script>

<script type="text/javascript">
	function loading_done_paginate (data)
	{
		console.log(data);
	}
</script>

<script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>