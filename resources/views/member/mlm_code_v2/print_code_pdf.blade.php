<table width="100%" style="margin-right: 15px;" class="{{$ctr = 0}}">
	@if(count($_item_product_code) > 0)
	<tr >
	@foreach($_item_product_code as $item_code)

		@if($type != 'register_form')
		<td {!! $ctr == 4 ? $ctr = 0 : '' !!} width="25%" style="border:1px #000 solid;padding:1px ;padding-top: 20px;padding-bottom: 20px;" class="{{$ctr++}} text-center">
			@if($type == 'pin_code')
			<img src="{{URL::to('/')}}/barcode?text={{ $item_code->mlm_pin.'@'.$item_code->mlm_activation }}&size=50">
			<div class="mlm_pin {{$on_show['pin_num']}}" style="float:left;width: 50%;padding-left:5px">{{$item_code->mlm_pin}}</div>
			<div class="mlm_activation {{$on_show['activation']}}" style="text-align:right;float:right;width: 50%;padding-right:5px">{{$item_code->mlm_activation}}</div>		
			<div class="item_name {{$on_show['membership_kit']}}" style="text-align:center;">{{ucwords($item_code->item_name)}}</div>
			<div class="membership_name {{$on_show['membership']}}" style="text-align:center;">{{ucwords($item_code->membership_name)}}</div>
			@endif
			@if($type == 'ref_number')
			<img src="{{URL::to('/')}}/barcode?text={{ 'REFNUM-'.$item_code->record_log_id }}&size=50">
			<small>{{'REFNUM-'.$item_code->record_log_id}}</small>
			<!-- <div class="mlm_pin {{$on_show['pin_num']}}">{{$item_code->mlm_pin}}</div>
			<div class="mlm_activation {{$on_show['activation']}}">{{$item_code->mlm_activation}}</div>	 -->	
			<div class="item_name {{$on_show['membership_kit']}}" style="text-align:center;">{{ucwords($item_code->item_name)}}</div>	
			<div class="membership_name {{$on_show['membership']}}" style="text-align:center;">{{ucwords($item_code->membership_name)}}</div>
			@endif
		</td>
		{!! $ctr == 4 ? "</tr><tr>" : '' !!}
		@else
		<!-- <td {!! $ctr == 3 ? $ctr = 0 : '' !!} width="25%" style="border:1px #000 solid;padding:10px ;padding-top: 20px;" class="{{$ctr++}} text-small">
			<div class="text-center"><strong>How to Register</strong></div><br>
			<div>
				1. Visit our website <a href="javascript:">{{$_SERVER['SERVER_NAME']}}</a>
			</div>
			<div>
				2. Navigate to join <em>"join the movement"</em>
			</div>
			<div>
				3. Fill up details to create your account
			</div>
			<div>
				4. Choose "Enter a Code" and enter your CODE and Activation
			</div>
			<div>
				5. Fill up step by step details
			</div>
			<br>
			<div style="text-align: center">
				<div>CODE</div>
				<div><a href="javascript:">{{$item_code->mlm_pin}}</a></div>
				<div>ACTIVATION</div>
				<div><a href="javascript:">{{$item_code->mlm_activation}}</a></div>
			</div>
			<br>
			<div class="text-center"><small>{{'REFNUM-'.$item_code->record_log_id}}</small></div>
		</td> -->
		
		<!-- {!! $ctr == 3 ? "</tr><tr>" : '' !!}	 -->
		<tr>
			<td  width="100%" class="text-center" >

				<br>
				<br>
				<br>
				<br>
				<div style="float:left;width: 50%;padding-left:5px">
				</div>
				<div style="float:right;width: 50%;padding-right:5px"><div class="text-center">{{str_pad($item_code->ctrl_number, 9, '0', STR_PAD_LEFT)}}</div></div></div>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<div class="text-center">
					<div> CODE </div>
					<div><strong>{{$item_code->mlm_pin}}</strong><div>
					<div>ACTIVATION<div>
					<div><strong>{{$item_code->mlm_activation}}</strong> <div>
				</div>
				<br>
			</td>
		</tr>
		@endif
	@endforeach
	</tr>
	@else
	<tr><td style="text-align: center">NO CODES</td></tr>
	@endif
	
</table>
<style type="text/css">
	tr { page-break-inside: avoid; }
	.text-small
	{
		font-size: 12px;
	}
</style>