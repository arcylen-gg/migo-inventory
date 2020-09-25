<div class="form-horizontal">
	<div class="form-group">
		<div class="col-md-12">
			<h4>Payslip<button class="btn btn-custom-primary pull-right popup" size="lg" link="/member/payroll/custom_payslip/modal_create_payslip">Create Payslip</button>

				<button class="btn btn-custom-primary pull-right popup" size="sm" link="/member/payroll/custom_payslip/modal_view_payslip_option" style="margin-right: 10px;"><i class="fa fa-cog"></i>&nbsp;Options</button></h4>
		</div>
	</div>
	<ul class="nav nav-tabs">
	  <li class="active"><a data-toggle="tab" href="#active-payslip"><i class="fa fa-star"></i>&nbsp;Active</a></li>
	  <li><a data-toggle="tab" href="#archived-payslip"><i class="fa fa-trash"></i>&nbsp;Archived</a></li>
	</ul>
	<div class="tab-content padding-top-10">
		<div id="active-payslip" class="tab-pane fade in active">
			<div class="form-group">
				<div class="col-md-4">
					<div class="panel panel-default background-white">
						<div class="panel-body">
							<div class="list-group">
								@foreach($_payslip as $payslip)
								<a href="#" link="/member/payroll/custom_payslip/custom_payslip_show/{{$payslip->payroll_payslip_id}}" class="list-group-item payslip-list-nav">{{$payslip->payslip_code}}</a>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="panel panel-default background-white">
						<div class="panel-body panel-payslip-show">
							
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="archived-payslip" class="tab-pane fade">
			<div class="form-group">
				<div class="col-md-4">
					<div class="panel panel-default background-white">
						<div class="panel-body">
							<div class="list-group">
								@foreach($_archived as $archived)
								<a href="#" link="/member/payroll/custom_payslip/custom_payslip_show_archived/{{$archived->payroll_payslip_id}}" class="list-group-item payslip-list-nav">{{$archived->payslip_code}}</a>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="panel panel-default background-white">
						<div class="panel-body panel-payslip-show">
							
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
<script type="text/javascript">
	a_nav_payslip();
	function a_nav_payslip()
	{
		$(".payslip-list-nav").unbind("click");
		$(".payslip-list-nav").bind('click', function(e)
		{
			e.preventDefault();
			$(".panel-payslip-show").html('<div class="loader-16-gray"></div>');
			var link = $(this).attr('link');
			// alert(link);
			$(".panel-payslip-show").load(link, function(){});
			$(".payslip-list-nav").removeClass('active');
			$(this).addClass('active');
		});
	}

</script>