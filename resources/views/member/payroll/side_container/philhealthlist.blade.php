<form class="form-horizontal global-submit" role="form" action="/member/payroll/philhealth_table_list/philhealth_table_save" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<h4>Philhealth Table</h4>
		<div class="load-data" target="value-id-1">
			<div id="value-id-1">
				<table class="table table-bordered table-condensed ">
					<tr>
						<td colspan="2">
							Salary Range
						</td>
						<td>
							Salary Base	
						</td>
						<td>
							Total Monthly Premium
						</td>
						<td>
							Employee Share
						</td>
						<td>
							Employer Share
						</td>
						
						<td width="5%"></td>
					</tr>

					<tbody class="tbl-philhealth">
						@foreach($_philhealth as $philhealth)
						<tr>
							<td>
								<input type="number" step="any" name="payroll_philhealth_min[]" class="border-none width-100 text-right" value="{{$philhealth->payroll_philhealth_min}}">
							</td>
							<td>
								<input type="number" step="any" name="payroll_philhealth_max[]" class="border-none width-100 text-right" value="{{$philhealth->payroll_philhealth_max}}">
							</td>
							<td>
								<input type="number" step="any" name="payroll_philhealth_base[]" class="border-none width-100 text-right" value="{{$philhealth->payroll_philhealth_base}}">
							</td>
							<td>
								<input type="number" step="any" name="payroll_philhealth_premium[]" class="border-none width-100 text-right" value="{{$philhealth->payroll_philhealth_premium}}">
							</td>
							<td>
								<input type="number" step="any" name="payroll_philhealth_ee_share[]" class="border-none width-100 text-right" value="{{$philhealth->payroll_philhealth_ee_share}}">
							</td>
							<td>
								<input type="number" step="any" name="payroll_philhealth_er_share[]" class="border-none width-100 text-right" value="{{$philhealth->payroll_philhealth_er_share}}">
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
								<input type="number" step="any" name="payroll_philhealth_min[]" class="border-none width-100 text-right">
							</td>
							<td>
								<input type="number" step="any" name="payroll_philhealth_max[]" class="border-none width-100 text-right">
							</td>
							<td>
								<input type="number" step="any" name="payroll_philhealth_base[]" class="border-none width-100 text-right" >
							</td>
							<td>
								<input type="number" step="any" name="payroll_philhealth_premium[]" class="border-none width-100 text-right">
							</td>
							<td>
								<input type="number" step="any" name="payroll_philhealth_ee_share[]" class="border-none width-100 text-right">
							</td>
							<td>
								<input type="number" step="any" name="payroll_philhealth_er_share[]" class="border-none width-100 text-right">
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
			$(".tbl-philhealth tr").unbind("click");
			$(".tbl-philhealth tr:last").bind("click", function()
			{

				$(".tbl-philhealth tr:last").unbind("click");
				var ref = $(".tbl-ref").html();
				$(".tbl-philhealth").append(ref);
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