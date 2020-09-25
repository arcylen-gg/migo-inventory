<form class="global-submit form-horizontal" role="form" action="/member/mlm/membership/edit/package/save/submit" id="save_membership_form" method="post">
@if($membership_packages)
<input type="hidden" name="membership_id" value="{{$membership_id}}">
<input type="hidden" name="membership_package_id" value="{{$membership_package_id}}">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Edit Package</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
	<div id="input-fields-horizontal">
	
			{!! csrf_field() !!}
			<div class="form-group">
				<label for="input-horizontal" class="col-lg-4 control-label">Package Name</label>
				<div class="col-lg-8">
					<input type="text" id="input-horizontal" name="membership_package_name" class="form-control membership_package_name" value="{{$package_name}}" placeholder="E.G Gold, Silver, Platinum" disabled="true">
				</div>
				<label for="input-horizontal" class="col-lg-4 control-label">Package Type</label>
                <div class="col-lg-8">
                    <select name="membership_package_is_gc" class="form-control membership_package_is_gc" onchange="membership_package_is_gc_on_change(this)">
                        <option value="0" {{$membership_packages->membership_package_is_gc == 0 ? 'selected' : ''}} >Bundle</option>
                        <option value="1" {{$membership_packages->membership_package_is_gc == 1 ? 'selected' : ''}} >GC</option>
                    </select>
                </div>
			</div>
		
	</div>
	<div style="margin-top: 10px; border: 1px solid #ddd;">
         <div class="table-responsive bundle_0 hide">
            <table class="table table-condensed">
                <thead style="text-transform: uppercase">
                    <tr>
                        <th>Product</th>
                        <th style="width: 100px;">Quantity</th>
                    </tr>
                </thead>
                <?php $ctr = 0; ?>
                <tbody class="add_line_body">
                	@if(count($item_count) != 0)
                		@foreach($item_count as $k => $i_list)
                			@if($ctr == 0)
		                	<tr class="add_line_body_first">
		                        <td>
		              				@if($items)
		                        	<select class="form-control" name="item_id[]">
		                        		@foreach($items as $i)
			                        		<option value="{{$i->item_id}}">{{$i->item_name}}</option>
		                        		@endforeach
		                        	</select>	
		                        	@endif
		                        </td>
		                        <td class="text-left">
		                        	<input type="text" class="form-control" value="1" name="quantity[]"/>
		                        </td>
		                    </tr>
		                    <tr class="second_line">
		                        <td>
		                        	@if($items)
		                        	<select class="form-control" name="item_id[]">
		                        		@foreach($items as $i)
			                        		<option value="{{$i->item_id}}" {{$k == $i->item_id ? "selected" : " " }}>{{$i->item_name}}</option>
		                        		@endforeach
		                        	</select>	
		                        	@endif
		                        </td>
		                        <td class="text-left">
		                        	<input type="text" class="form-control" value="{{$item_count[$k]}}" name="quantity[]"/>
		                        </td>
		                    </tr>
		                    <?php $ctr++ ?>
		                    @else
		                    <tr class="second_line">
		                        <td>
		                        	@if($items)
		                        	<select class="form-control" name="item_id[]">
		                        		@foreach($items as $i)
			                        		<option value="{{$i->item_id}}" {{$k == $i->item_id ? "selected" : " " }}>{{$i->item_name}}</option>
		                        		@endforeach
		                        	</select>	
		                        	@endif
		                        </td>
		                        <td class="text-left">
		                        	<input type="text" class="form-control" value="{{$item_count[$k]}}" name="quantity[]"/>
		                        </td>
		                    </tr>
		                    @endif
                    	@endforeach
                   	@else
                   	<tr class="add_line_body_first">
						<td>
							@if($items)
							<select class="form-control" name="item_id[]">
								@foreach($items as $i)
						    		<option value="{{$i->item_id}}">{{$i->item_name}}</option>
								@endforeach
							</select>	
							@endif
						</td>
						<td class="text-left">
							<input type="text" class="form-control" value="1" name="quantity[]"/>
						</td>
					</tr>	
                   	@endif
                </tbody>
                <tbody>
                    <tr>
                    	<td colspan="2" class="text-right"><a href="javascript:" onClick="add_line()">Add Line</a> | <a href="javascript:" onClick="clear_all_line()">Clear All Lines</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive bundle_1 hide">
            <table class="table">
            <th>GC AMOUNT</th>
            <tr>
                <td><input type="number" class="form-control" name="membership_package_gc_amount" value="{{$membership_packages->membership_package_gc_amount}}"></td>
            </tr>
            </table>
        </div>
	</div>        
</div>
<div class="add_line_body_first">
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
	<button class="btn btn-primary add_new_package_submit btn-custom-primary" type="button" onClick="submit_form()">Save Package</button>
</div>
@else
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Edit Package</h4>
</div>
<div class="modal-body add_new_package_modal_body clearfix">
<center>Invalid Package</center>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>	
@endif
</form>
<script type="text/javascript">
@if($membership_packages->membership_package_is_gc == 0)
$('.bundle_0').removeClass('hide');
$('.bundle_1').addClass('hide');
@elseif($membership_packages->membership_package_is_gc == 1)
$('.bundle_0').addClass('hide');
$('.bundle_1').removeClass('hide');
@endif
var first_line = $('.add_line_body_first').html();
$('.add_line_body_first').html(" ");
function add_line()
{
	$('.add_line_body').append('<tr class="second_line">' + first_line + '</tr>');
	$('.add_line_body_first').html(" ");
}
function clear_all_line()
{
	$('.second_line').remove();
	$('.add_line_body_first').remove();
}
function submit_form()
{
	$('.membership_package_name').attr('disabled', false);
	$('#save_membership_form').submit();	
	$('.membership_package_name').attr('disabled', true);
}
function membership_package_is_gc_on_change(ito)
{
    var hide = $(ito).val();
    if(hide == 0)
    {
        $('.bundle_0').removeClass('hide');
        $('.bundle_1').addClass('hide');
    }
    else if(hide == 1)
    {
        $('.bundle_0').addClass('hide');
        $('.bundle_1').removeClass('hide');
    }
}
</script>