@extends("member.member_layout")
@section("member_content")
<div class="wallet-encashment">
	<div class="main-member-header clearfix">
		<div class="left">
			<div class="icon">
				<img src="/themes/{{ $shop_theme }}/img/wallet-encashment.png">
			</div>
			<div class="text">
				<div class="name">
					<div>Wallet Transfer</div>
				</div>
				<div class="sub">In this tab you can request/view wallet transfer history. </div>
			</div>
		</div>
		<div class="right">
			<div class="text-right">
				<!-- <button type="button" class="btn btn-default popup" link="/members/payout-setting" size="md"><i class="fa fa-bank"></i> PAYOUT METHOD</button> -->
				<button type="button" class="btn btn-primary popup" link="/members/wallet-transfer-request" size="md"><i class="fa fa-money"></i> TRANSFER WALLET</button>
			</div>
		</div>
	</div>
	<div class="wallet-encashment-content">
		<div class="title">
			Transfer History
		</div>

		<div class="table-holder table-responsive">
			<table class="table table-striped">
				<thead>
					@if(count($transfer_history)>0)
					<tr>
						<th class="text-center" width="200px">Date</th>
						<th class="text-center">Slot</th>
						<th class="text-left">Details</th>
						<th class="text-right" width="200px">Status</th>
					</tr>
					@else
					<tr>
						<th class="text-center" colspan="3">No Transfer History</th>
					</tr>
					@endif
				</thead>
				<tbody>
					
				</tbody>
				<tfoot style="background-color: #f3f3f3; font-size: 15px;">
				@foreach($transfer_history as $history)
				<tr>
					<td class="text-center">{{ $history->wallet_log_date_created }}</td>
					<td class="text-center">{{ $history->slot_no }}</td>
					<td class="text-left"><b>{{ $history->wallet_log_details }}</b></td>
					<td class="text-right"> {{ $history->wallet_log_status }} </td>
				</tr>
				@endforeach
				
				</tfoot>
			</table>
			<div class="clearfix">
				<div class="pull-right">
				{!! $transfer_history->render() !!}
				</div>
			</div>
		</div>
	</div>
</div>

<!-- MANUAL PLACING OF SLOT -->
<div class="popup-wallet-encashment">
    <div id="wallet-encashmnet-modal" class="modal fade">
        <div class="modal-md modal-dialog">
            <div class="modal-content">
                
              </div>
          </div>
      </div>
  </div>

@endsection
@section("member_script")
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/wallet_encashment.js"></script>
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/wallet_encashment.css">
@endsection