@extends('member.layout')
@section('content')
<form class="global-submit" action="{{$action or ''}}" method="post">
    <div class="panel panel-default panel-block panel-title-block">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
        <div class="panel-heading">
            <div>
                <i class="fa fa-calendar"></i>
                <h1>
                <span class="page-title">Landing Cost</span>
                <small>
                Insert Description Here
                </small>
                </h1>
                <div class="dropdown pull-right">
                    <div>
                    	<button type="submit" class="btn btn-primary"><i class="fa fa-icon fa-star"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
        <div class="data-container">
            <div class="tab-content">
                <div class="row">      
                    <div class="col-md-12" style="padding: 30px;">
		            	<div class="row clearfix draggable-container">
		                    <div class="table-responsive">
		                        <div class="col-sm-12">
		                            <table class="digima-table">
		                                <thead>
		                                	<tr>
		                                		<th class="text-center">#</th>
		                                		<th class="text-center">Name</th>
		                                		<th class="text-center">Description</th>
		                                		<th class="text-center">Type</th>
                                                <th class="text-center"></th>
		                                	</tr>
		                                </thead>
                                        <tbody class="draggable tbody-item">
                                            @if(count($_landing_cost) > 0)
                                                @foreach($_landing_cost as $key => $cost)
                                                <tr>
                                                    <td class="text-center"><label class="number-td">1</label></td>
                                                    <td><input type="text" class="form-control" name="cost_name[]" value="{{$cost->default_cost_name}}" /></td>
                                                    <td><textarea class="form-control textarea-expand" name="cost_description[]">{{$cost->default_cost_description}}</textarea></td>
                                                    <td>
                                                        @if($key != 0)
                                                        <select class="form-control" name="cost_type[]">
                                                            <option value="fixed" {{$cost->default_cost_type == 'fixed' ? 'selected' : ''}}>Fixed</option>
                                                            <option value="percentage" {{$cost->default_cost_type == 'percentage' ? 'selected' : ''}}>Percentage</option>
                                                        </select>
                                                        @else
                                                        <select class="hidden form-control" name="cost_type[]">
                                                            <option value=""></option>
                                                        </select>
                                                        @endif
                                                    </td>
                                                    <td class="text-center {{$key != 0 ? 'remove-tr' : ''}} cursor-pointer"><i class="fa fa-trash-o {{$key != 0 ? '' : 'hidden'}}" aria-hidden="true"></i></td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        	<tr>
                                        		<td class="text-center"><label class="number-td">2</label></td>
                                        		<td><input type="text" class="form-control" name="cost_name[]"/></td>
                                        		<td><textarea class="form-control textarea-expand" name="cost_description[]"></textarea></td>
                                        		<td>
                                        			<select class="form-control" name="cost_type[]">
                                        				<option value="fixed">Fixed</option>
                                        				<option value="percentage">Percentage</option>
                                        			</select>
                                        		</td>
                                                <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
                                        	</tr>
                                       	</tbody>
		                            </table>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
            </div>
        </div>
    </div>
</form>


<div class="div-script">
    <table class="div-item-row-script hide">
        <tr>
            <td class="text-center"><label class="number-td">1</label></td>
            <td><input type="text" class="form-control" name="cost_name[]"/></td>
            <td><textarea class="form-control textarea-expand" name="cost_description[]"></textarea></td>
            <td>
                <select class="form-control" name="cost_type[]">
                    <option value="fixed">Fixed</option>
                    <option value="percentage">Percentage</option>
                </select>
            </td>
            <td class="text-center remove-tr cursor-pointer"><i class="fa fa-trash-o" aria-hidden="true"></i></td>
        </tr>
    </table>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/landing_cost/default_landing_cost.js"></script>
@endsection