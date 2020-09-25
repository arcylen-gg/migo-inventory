<form class="global-submit" rold="form" action="/member/mlm/payout/config" method="post">
	{{ csrf_field() }}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">PAYOUT CONFIGURATION</h4>
	</div>
	<div class="modal-body clearfix">
		<div class="row">
			<div class="clearfix modal-body">
				<div class="form-horizontal">
					<div class="form-group">
						<div class="text-center"><div style="font-weight: bold; padding-bottom: 10px; font-size: 16px;">PAYOUT CHARGES</div></div>
						<div class="col-md-3">
							<label for="basic-input">Tax (%)</label>
							<input id="basic-input" value="{{ $settings->enchasment_settings_tax }}" class="form-control text-right" name="enchasment_settings_tax" placeholder="">
						</div>
						<div class="col-md-3">
							<label for="basic-input">Service Charge (% for Pct.)</label>
							<input type="hidden" class="service_fee_type" name="enchasment_settings_p_fee_type" value="{{ $settings->enchasment_settings_p_fee_type }}">
							<input id="basic-input" value="{{ $settings->enchasment_settings_p_fee }}" class="form-control text-right change-service-type" name="enchasment_settings_p_fee" placeholder="">
						</div>
						<div class="col-md-3">
							<label for="basic-input">Other Charge (PHP)</label>
							<input id="basic-input" value="{{ $settings->encashment_settings_o_fee }}" class="form-control text-right" name="encashment_settings_o_fee" placeholder="">
						</div>
						<div class="col-md-3">
							<label for="basic-input">Minimum Encashment</label>
							<input id="basic-input" value="{{ $settings->enchasment_settings_minimum }}" class="form-control text-right" name="enchasment_settings_minimum" placeholder="">
						</div>
					</div>
				</div>
				<div class="form-horizontal" style="margin-top: 30px">
					<div class="form-group">
						<div class="col-md-6">
							<div style="font-weight: bold; padding-bottom: 10px; font-size: 16px;">METHOD LIST</div>
							@if($shop_id == 1)
								<div><label><input {{ hasWord('vmoney', $user_info->shop_payout_method) ? 'checked' : '' }} class="payout_method" value="vmoney" type="checkbox" name="payout_method[]"> V-MONEY</label></div>
								<div><label><input {{ hasWord('airline', $user_info->shop_payout_method) ? 'checked' : '' }} class="payout_method" value="airline" type="checkbox" name="payout_method[]"> AIRLINE WALLET</label></div>
							@endif
							<div><label><input {{ hasWord('bank', $user_info->shop_payout_method) ? 'checked' : '' }} class="payout_method" value="bank" type="checkbox" name="payout_method[]"> BANK DEPOSIT</label></div>
							<div><label><input {{ hasWord('cheque', $user_info->shop_payout_method) ? 'checked' : '' }} class="payout_method" value="cheque" type="checkbox" name="payout_method[]"> CHEQUE</label></div>
							<div><label><input {{ hasWord('eon', $user_info->shop_payout_method) ? 'checked' : '' }} class="payout_method" value="eon" type="checkbox" name="payout_method[]"> EON</label></div>
							<div><label><input {{ hasWord('palawan_express', $user_info->shop_payout_method) ? 'checked' : '' }} class="payout_method" value="palawan_express" type="checkbox" name="payout_method[]"> PALAWAN EXPRESS</label></div>
							<div><label><input {{ hasWord('coinsph', $user_info->shop_payout_method) ? 'checked' : '' }} class="payout_method" value="coinsph" type="checkbox" name="payout_method[]"> COINS.PH</label></div>
						</div>
						<div class="col-md-6">
							<div style="font-weight: bold; padding-bottom: 10px; font-size: 16px;">BANK LIST</div>
							@foreach($_bank as $bank)
							<div><label><input {{ $bank->enabled ? 'checked' : '' }} class="payout_method" name="bank[]" value="{{ $bank->payout_bank_id }}" type="checkbox"> {{ strtoupper($bank->payout_bank_name) }}</label></div>
							@endforeach
						</div>
					</div>
				</div>
				<div class="form-horizontal">
					<div class="form-group">		
						<div class="col-md-12" style="margin-bottom: 15px;">
							<div class="text-left"><div style="font-weight: bold; padding-bottom: 10px; font-size: 16px; margin-top: 25px;">SCHEDULED REQUEST PAYOUT</div></div>
							<label for="basic-input">Schedule Type</label>
							<div class="schedule-type-container">
								<label class="radio-inline"><input {{ $settings->encashment_settings_schedule_type == "none" ? "checked" : "" }} class="schedule-type" type="radio" value="none" name="encashment_settings_schedule_type">None</label>
								<label class="radio-inline"><input {{ $settings->encashment_settings_schedule_type == "weekly" ? "checked" : "" }} class="schedule-type" type="radio" value="weekly" name="encashment_settings_schedule_type">Weekly</label>
								<label class="radio-inline"><input {{ $settings->encashment_settings_schedule_type == "monthly" ? "checked" : "" }} class="schedule-type" type="radio" value="monthly" name="encashment_settings_schedule_type">Monthly</label>
							</div>
						</div>
						<div class="col-md-12 schedule-type-weekly hide">
							<label for="basic-input">Weekly Schedule</label>
							<div class="schedule-weekly">
								@if(is_serialized($settings->encashment_settings_schedule) && $settings->encashment_settings_schedule_type == "weekly")
									@foreach(unserialize($settings->encashment_settings_schedule) as $key => $schedule)
										<input name="encashment_settings_schedule[{{ $key }}]" type="hidden" value="false">
										<label style="margin-right: 10px;" class="checkbox-inline"><input {{ $schedule == "true" ? "checked" : "false" }} type="checkbox" value="true" name="encashment_settings_schedule[{{ $key }}]">{{ date('l', strtotime("Sunday +{$key} days")) }}</label>
									@endforeach
								@else
									@for($i = 0; $i <= 6; $i++)
										<input name="encashment_settings_schedule[{{ $i }}]" type="hidden" value="false">
										<label style="margin-right: 10px;" class="checkbox-inline"><input type="checkbox" value="true" name="encashment_settings_schedule[{{ $i }}]">{{ date('l', strtotime("Sunday +{$i} days")) }}</label>
									@endfor
								@endif
							</div>
						</div>
						<div class="col-md-12 schedule-type-monthly hide">
							<label for="basic-input">Monthly Schedule</label>
							<div class="schedule-monthly">
								@if(is_serialized($settings->encashment_settings_schedule) && $settings->encashment_settings_schedule_type == "monthly")
									@foreach(unserialize($settings->encashment_settings_schedule) as $key => $schedule)
										<input name="encashment_settings_schedule[{{ $key }}]" type="hidden" value="false">
										<label style="margin-right: 10px;" class="checkbox-inline"><input {{ $schedule == "true" ? "checked" : "false" }} type="checkbox" value="true" name="encashment_settings_schedule[{{ $key }}]">Every {{ ordinal($key) }} of the Month</label>
									@endforeach
								@else
									@for($i = 1; $i <= 30; $i++)
										<input name="encashment_settings_schedule[{{ $i }}]" type="hidden" value="false">
										<label style="margin-right: 10px;" class="checkbox-inline"><input type="checkbox" value="true" name="encashment_settings_schedule[{{ $i }}]">Every {{ ordinal($i) }} of the Month</label>
									@endfor
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary btn-custom-primary" type="button">Update Payout Settings</button>
	</div>
</form>
<script type="text/javascript">
	function payout_config_success(data)
	{
		data.element.modal("hide");
	}
</script>
<script type="text/javascript" src="/assets/member/js/payout_config.js"></script>