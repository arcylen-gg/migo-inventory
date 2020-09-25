<table class="table table-bordered table-condensed">
	<thead>
		<tr>
			<th>Type Name</th>
			<th width="5%"></th>
		</tr>
	</thead>
	<tbody class="padding-tb-1">
		@foreach($_active as $active)
		<tr>
			<td>
				<input type="text" name="" class="border-none width-100 padding-5 txt-deduction-type" value="{{$active->payroll_deduction_type_name}}" data-content="{{$active->payroll_deduction_type_id}}">
			</td>
			<td class="text-center">

				<a href="#" class="btn-archived" data-archived="{{$active->payroll_deduction_archived == 0 ? '1':'0'}}" data-content="{{$active->payroll_deduction_type_id}}"><i class="fa fa-{{$active->payroll_deduction_archived == 0 ? 'times':'refresh'}}"></i></a>
			</td>
		</tr>
		@endforeach
	</tbody>
</table>