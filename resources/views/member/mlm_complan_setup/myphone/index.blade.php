<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
    	<div class="col-md-12">
    		<form class="global-submit" method="post" action="/member/mlm/complan_setup/settings/update/myphone">
    		<table class="table table-responsive table-bordered">
    			<thead>
    				<th>
    					Settings
    				</th>
    				<th>
    					Status
    				</th>
    				<th>
    					
    				</th>
    			</thead>
    			<tbody>
    				@if(isset($settings_myphone_require_sponsor))
    					@if($settings_myphone_require_sponsor)
							<tr>
								<td>
									Require Sponsor
									<input type="hidden" value="{{$settings_myphone_require_sponsor->settings_key}}" name="settings_key">
								</td>
								<td>
									<select class="form-control" name="settings_value">
										<option value="0" {{$settings_myphone_require_sponsor->settings_value == 0 ? 'selected' : ''}}>Disable</option>
										<option value="1" {{$settings_myphone_require_sponsor->settings_value == 1 ? 'selected' : ''}} >Enable</option>
									</select>
								</td>
								<td>
									<button class="btn btn-primary">Submit</button>
								</td>
							</tr>
						@endif
    				@endif
    			</tbody>
    		</table>

    	</div>
    </div>
</div>    