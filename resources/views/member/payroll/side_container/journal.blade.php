<h4>Payroll Journal Tags<button class="btn btn-custom-primary pull-right popup" link="/member/payroll/payroll_jouarnal/modal_create_journal_tag">Create Journal Tags</button></h4>
<br>
<div class="form-group">
	<div class="col-md-12">
		<table class="table table-bordered table-condensed">
			<thead>
				<tr>
					<th class="text-center">Account Number</th>
					<th class="text-center">Account Name</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach($_tag as $tag)
				<tr>
					<td class="text-center">
						{{$tag->account_number}}
					</td>
					<td>
						{{$tag->account_name}}
					</td>
					<td class="text-center">
						<div class="dropdown">
							<button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
							<span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-custom">
								<li>
									<a href="#" class="popup" link="/member/payroll/payroll_jouarnal/modal_edit_journal_tag/{{$tag->payroll_journal_tag_id}}"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
								</li>
								<li>
									<a href="#" class="popup" link="/member/payroll/payroll_jouarnal/modal_confimr_del_journal_tag/{{$tag->payroll_journal_tag_id}}" size="sm"><i class="fa fa-trash-o"></i>&nbsp;Archived</a>
								</li>
							</ul>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
