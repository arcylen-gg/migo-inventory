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
            
            <a href="javascript:" size="lg" class="panel-buttons btn btn-custom-primary pull-right popup" link="/member/item/unit_of_measurement/add" >Add U/M</a>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
	<div class="panel-body form-horizontal">
		<div class="form-group">
			<div class="col-md-6">
				<ul class="nav nav-tabs">
				  <li  id="all-list" class="active"><a data-toggle="tab" href="#all"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active U/M</a></li>
				  <li id="archived-list"><a data-toggle="tab" href="#archive"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Archived U/M</a></li>
				</ul>
			</div>
			<div class="col-md-4 pull-right">
				<div class="input-group">
					<span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
					<input type="search" name="" class="form-control" placeholder="Start typing category">
				</div>
			</div>
		</div>
		<div class="form-group panel-body tab-content unit-m-container">

            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>U/M Set Name</th>
								<th class="text-center">Base U/M</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody class="table-category">
							@if($_um)
								@foreach($_um as $um)
									<tr>
										<td>{{$um->um_name}}</td>
										<td>{{$um->um_base_name}} ({{$um->um_base_abbrev}})</td>
										<td class="text-center">
											<div class="btn-group">
			                                    <a link="/member/item/unit_of_measurement/edit/{{$um->um_id}}" size="lg" class="btn btn-primary btn-grp-primary popup">Edit</a>
			                                    <a class="btn btn-primary btn-grp-primary popup" link="/member/item/unit_of_measurement/{{$um->um_id}}/archive" size="md" href="javascript:"> <span class="fa fa-trash "> </span> </a>
			                                </div>
										</td>

									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
			<div id="archive" class="tab-pane fade in">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
					<table class="table table-bordered table-condensed">
						<thead>
							<tr>
								<th>U/M Set Name</th>
								<th class="text-center">Base U/M</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody class="table-category">
							@if($_um_archived)
								@foreach($_um_archived as $um_a)
									<tr>
										<td>{{$um_a->um_name}}</td>
										<td>{{$um_a->um_base_name}} ({{$um_a->um_base_abbrev}})</td>
										<td class="text-center">
											<div class="btn-group">
			                                    <a class="btn btn-primary btn-grp-primary popup" link="/member/item/unit_of_measurement/{{$um_a->um_id}}/restore" size="md" href="javascript:"> Restore </a>
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
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/manage_category_list.js"></script>
<script type="text/javascript" src="/assets/member/js/unit_measurement.js"></script>
<script type="text/javascript">
	
function submit_done(data)
{	
	if(data.status == "success")
    {
        toastr.success("Success");
        $(".unit-m-container").load("/member/item/unit_of_measurement .unit-m-container");
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