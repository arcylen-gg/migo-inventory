@if(Request::input('pdf') == 'true')
<link rel="stylesheet" type="text/css" href="{{Request::input('pdf') == 'true' ? public_path() : ''}}/assets/card/style.css">
<link href="https://fonts.googleapis.com/css?family=Poppins:300,600" rel="stylesheet">  
@endif
<div class="{{ Request::input('pdf') == 'true' ? '' : 'row' }} clearfix">

<div class="{{ Request::input('pdf') == 'true' ? '' : 'col-md-6' }} clearfix" style="{{ Request::input('pdf') == 'true' ? 'margin-top: 450px;' : '' }}">
	<div class="containers" style="overflow: hidden; {{ Request::input('pdf') == 'true' ? '-webkit-transform: scale(5,5); transform: scale(5,5);' : '' }} ;height: 276px; background-color: transparent; background-image: url('{{Request::input('pdf') == 'true' ? URL::to('/') : ''}}/assets/card/images/BG-{{ $color }}.jpg') !important;">
		<div class="top-container clearfix">
			<span class="website">{{ URL::to('/') }}</span>
			<div class="logo"><img style="width: 180px;" src="{{Request::input('pdf') == 'true' ? public_path() : ''}}/assets/card/images/philtech-logo.png"></div>
		</div>
		<div class="mid-container clearfix" style="margin: 0; height: 149px;"><img style="display: block; width: 380px; margin: auto;" src="{{Request::input('pdf') == 'true' ? public_path() : ''}}/assets/card/images/{{ $color }}-logo.png"></div>
		<div class="bottom-container clearfix" style="margin-top: -7.5px;">
			<div class="member">
				<div class="member-name">{{ $name }}</div>
				<div class="member-label">MEMBER</div>
				<div class="member-label">ISSUED: <small>{{$now}}</small></div>
			</div>
			<div class="barcode">
				<div class="barcodeimg" style="background-color: #fff; padding: 7.5px 0;"><img src="{{Request::input('pdf') == 'true' ? URL::to('/') : ''}}/barcode?text={{ $membership_code }}&size=35"></div>
				<div class="barcodetxt">
					<span>Membership No.</span>
					<span>{{ $membership_code }}</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="{{ Request::input('pdf') == 'true' ? '' : 'col-md-6' }} clearfix" style="{{ Request::input('pdf') == 'true' ? 'margin-top: 1000px;' : '' }}">
	<div class="containers" style="{{ Request::input('pdf') == 'true' ? '-webkit-transform: scale(5,5); transform: scale(5,5);' : '' }} height: 276px; background-color: transparent; background-image: url('{{Request::input('pdf') == 'true' ? URL::to('/') : ''}}/assets/card/images/BG-{{ $color }}.jpg') !important;">
		<div class="backdesu">
			<div>For inquiries, email at philtechglobalmainoffice@gmail.com</div>
			<div class="signature">
				<div class="sign-label">Cardholder's Signature</div>
			</div>
			<div class="info">
				<table>
					<tbody>
						<tr>
							<td style="width: 100px;">Date Activated:</td>
							<td>{{$info->slot_created_date}}</td>
						</tr>
					</tbody>
				</table>
				<table>
					<tbody>
						<tr>
							<td style="width: 65px;">Phone No.</td>
							<td>{{$number}}</td>
						</tr>
					</tbody>
				</table>
				<table>
					<tbody>
						<tr>
							<td style="width: 130px;">Complete Address:</td>
							<td>{{ mb_strimwidth($address, 0, 45, "...")}}</td>
						</tr>
					</tbody>
				</table>
				<table>
					<tbody>
						<tr>
							<td style="width: 320px;">In case of loss please notify PHILTECH Head Office:</td>
							<td> <small>(+63)9175422614</small></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="margin-bottom: 7.5px;">By signing this card, the cardholder agrees to be bound be the terms and conditions of the VIP Loyalty Program. Present this card along with the valid ID when purchasing or availing privilleges and benefits in the head office, all BCO and partner merchants nationwide.</div>
			<div style="margin-bottom: 7.5px;">Non-Transferable&nbsp;&nbsp;&#149;&nbsp;&nbsp;No Annual Fee&nbsp;&nbsp;&#149;&nbsp;&nbsp;No Expiry</div>
			<div class="text-right">Tampering invalidates the card.</div>
		</div>
	</div>
	<br>
</div>

</div>
@if(Request::input('pdf') != 'true')
<div class="col-md-12">
	<hr>
	<div class="col-md-3">
		{{ $name }}
		Status : @if($info->slot_card_printed == 1) PRINTED @else PENDING @endif
	</div>
	<div class="col-md-3">
		<a class="btn btn-primary" href="/member/mlm/card/image/{{$info->slot_id}}?pdf=true" target="_blank">Export to JPG</a>
	</div>
	<div class="col-md-3">
		<form class="global-submit" method="post" action="/member/mlm/card/done">
		{!! csrf_field() !!}
		<input type="hidden" name="slot_id" value="{{$info->slot_id}}">
		<button class="btn btn-primary" @if($info->slot_card_printed == 1) disabled @endif >Mark Done</button>
		</form>
	</div>
	<div class="col-md-3">
	<form class="global-submit" method="post" action="/member/mlm/card/pending">
		{!! csrf_field() !!}
		<input type="hidden" name="slot_id" value="{{$info->slot_id}}">
		<button class="btn btn-primary"  @if($info->slot_card_printed == 0) disabled @endif >Mark Pending</button>
		</form>
	</div>
	
	
</div>
@endif