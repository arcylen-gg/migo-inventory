@extends('member.layout')

@section('css')
@endsection

@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-line-chart"></i>
            <h1>
                <span class="page-title">Unit Of Measurements</span>
                <small>
	                Manage your unit of measurements.
                </small>
            </h1>
            <!-- 
            <a href="javascript:" size="lg" class="panel-buttons btn btn-custom-primary pull-right popup" link="/member/item/unit_of_measurement/add" >Add U/M</a> -->
        </div>
    </div>
</div>
<div class="row unit-m-container">
	<div class="col-md-6 col-xs-12">
		<div class="panel panel-default panel-block panel-title-block">
			<div class="panel-body form-horizontal">
				<div class="form-group panel-body tab-content">			
					<div class="form-group">
						<div class="col-md-12">
							<strong>Unit of Measurement</strong>
							<a class="btn btn-primary popup pull-right" size="md" link="/member/pis/um_add?um_type=notbase">Add U/M</a>
						</div>
					</div>
		            <div id="all" class="tab-pane fade in active">
		                <div class="form-group order-tags"></div>
		                <div class="table-responsive">
							<table class="table table-bordered table-condensed">
								<thead>
									<tr>
										<th>U/M Name</th>
										<th class="text-center">U/M Abbrev</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody class="table-category">
									@if($_um_n)
										@foreach($_um_n as $um)
											<tr>
												<td>{{$um->um_name}}</td>
												<td>{{$um->um_abbrev}}</td>
												<td class="text-center">
													<div class="btn-group">
					                                    <a link="/member/pis/um_edit/{{$um->id}}" size="md" class="btn btn-primary btn-grp-primary popup">Edit</a>
					                                </div>
												</td>

											</tr>
										@endforeach
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-xs-12">
		<div class="panel panel-default panel-block panel-title-block">
			<div class="panel-body form-horizontal">
				<div class="form-group panel-body tab-content">
					<div class="form-group">
						<div class="col-md-12">
							<strong>Unit</strong>
							<a class="btn btn-primary popup pull-right" size="md" link="/member/pis/um_add?um_type=base">Add Unit</a>
						</div>
					</div>
		            <div id="all" class="tab-pane fade in active">
		                <div class="form-group order-tags"></div>
		                <div class="table-responsive">
							<table class="table table-bordered table-condensed">
								<thead>
									<tr>
										<th>U/M Name</th>
										<th class="text-center">U/M Abbrev</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody class="table-category">
									@if($_um_n_b)
										@foreach($_um_n_b as $um_b)
											<tr>
												<td>{{$um_b->um_name}}</td>
												<td>{{$um_b->um_abbrev}}</td>
												<td class="text-center">
													<div class="btn-group">
					                                    <a link="/member/pis/um_edit/{{$um_b->id}}" size="md" class="btn btn-primary btn-grp-primary popup">Edit</a>
					                                </div>
												</td>

											</tr>
										@endforeach
									@endif
								</tbody>
							</table>
						</div>
					</div>	
				</div>
				
			</div>
		</div>
		
	</div>
</div>

@endsection

@section('script')
<script type="text/javascript">
function submit_done(data)
{	
	if(data.type == "pis-um")
    {
        toastr.success("Success");
        $(".unit-m-container").load("/member/item/pis_unit_of_measurement .unit-m-container");
        data.element.modal("hide");
        $("#all-list").addClass("active");
        $("#archived-list").removeClass("active");
    }
    else if(data.status == "error")
    {
        toastr.warning(data.status_message);
        $(data.target).html(data.view);
    }
}
</script>
@endsection