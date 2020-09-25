@extends('member.layout')

@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-line-chart"></i>
            <h1>
                <span class="page-title">Unit Of Measurements Types</span>
                <small>
	                Manage your U/M Types.
                </small>
            </h1>
            
            <a size="md" class="panel-buttons btn btn-custom-primary pull-right popup" link="/member/item/um_type/add" >Add U/M Type</a>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
	<div class="panel-body form-horizontal">
		<div class="form-group">
			<div class="col-md-6 col-xs-12">
				<ul class="nav nav-tabs">
				  <li class="active"><a data-toggle="tab" href="#"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active U/M Type</a></li>
			<!-- 	  <li><a data-toggle="tab" href="#"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Archived U/M Type</a></li> -->
				</ul>
			</div>
			<!-- <div class="col-md-4 pull-right">
				<div class="input-group">
					<span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
					<input type="search" name="" class="form-control" placeholder="Start typing category">
				</div>
			</div> -->
		</div>
		<div class="form-group panel-body um-container">
			<table class="table table-bordered table-condensed">
				<thead>
					<tr>
						<th class="text-center">U/M Type Name</th>
						<th class="text-center">U/M Type Abbreviation</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody class="table-category">
					{!! $um_type !!}
				</tbody>
			</table>
		</div>
		
	</div>
</div>
@endsection
