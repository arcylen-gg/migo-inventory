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
					<div>Wallet Payout</div>
				</div>
				<div class="sub">In this tab you can request/view encashment history. </div>
			</div>
		</div>
		<div class="right">
			<div class="text-right">
				<button onclick="action_load_link_to_modal('/members/payout-setting', 'lg')" type="button" class="btn btn-gear"><i class="fa fa-bank"></i> PAYOUT SETTINGS</button>
				<button onclick="action_load_link_to_modal('/members/request-payout','md')" type="button" class="btn btn-primary"><i class="fa fa-credit-card"></i> REQUEST PAYOUT</button>
			</div>
		</div>
	</div>
	<div class="wallet-encashment-content">
		<div class="title">
			Encashment History
		</div>
		<div class="table-holder table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="text-left" width="200px">Date</th>
						<th class="text-center">SLOT</th>
						<th class="text-center" width="100px">Method</th>
						<th class="text-center" width="200px">Status</th>
						<th class="text-right" width="180px">Amount</th>
						<th class="text-right" width="150px">Tax</th>
						<th class="text-right" width="150px">Fee</th>
						<th class="text-right" width="180px">Sub Total</th>
					</tr>
				</thead>
				<tbody>
					@foreach($_encashment as $encashment)
					<tr>
						<td class="text-left">{{ $encashment->display_date }}</td>
		  				<td class="text-center">
		  					<div>{{ $encashment->slot_no }}</div>
		  				</td>
						<td class="text-center">{!! $encashment->log !!}</td>
						<td class="text-center"><b>{{ $encashment->wallet_log_payout_status }}</b></td>
						<td class="text-right"><b>{!! $encashment->display_wallet_log_request !!}</b></td>
						<td class="text-right">{!! $encashment->display_wallet_log_tax !!}</td>
						<td class="text-right">{!! $encashment->display_wallet_log_service_charge !!}</td>
						<td class="text-right"><a href='javascript:'><b>{!! $encashment->display_wallet_log_amount !!}</b></a></td>
					</tr>
					@endforeach
				</tbody>
				<tfoot style="background-color: #f3f3f3; font-size: 15px;">
					<tr>
						<td class="text-right"></td>
						<td class="text-right"></td>
						<td class="text-right"></td>
						<td class="text-right"></td>
						<td class="text-right"></td>
						<td class="text-center"></td>
						<td class="text-center"><b></b></td>
						<td class="text-right"><b>{{ $total_payout }}</b></td>
					</tr>
				</tfoot>
			</table>
		  	<div class="clearfix">
			  	<div class="pull-right">
			  		{!! session('payout_paginate') !!}
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
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-credit-card"></i> ENCASHMENT</h4>
                </div>
				<div class="modal-body clearfix">
					<div class="row">
				        <div class="clearfix modal-body"> 
				            <div class="form-horizontal">
				                <div class="form-group">
				                    <div class="col-md-8">
				                        <label for="basic-input">Choose a Payout Method</label>
				                        <select class="form-control">
				                        	<option>BANK DEPOSIT</option>
				                        	<option>EON CARD</option>
				                        </select>
				                    </div>
				                    <div class="col-md-4">
				                        <label for="basic-input">Amount</label>
				                        <input id="basic-input" value="5000" class="form-control text-right" name="item_sku" placeholder="">
				                    </div>
				                </div>
				                <div class="form-group">
				                    <div class="col-md-4">
				                        <label for="basic-input">Bank Name</label>
				                        <select class="form-control">
				                        	<option>BDO (Banco de Oro)</option>
				                        	<option>BPI (Bank of the Philippiens Island)</option>
				                        	<option>China Bank</option>
				                        	<option>Metro Bank</option>
				                        </select>
				                    </div>
				                    <div class="col-md-4">
				                        <label for="basic-input">Account Name</label>
				                        <input type="text" class="form-control" name="">
				                    </div>
				                    <div class="col-md-4">
				                        <label for="basic-input">Account Number</label>
				                        <input id="basic-input" value="" class="form-control text-right" name="item_sku" placeholder="">
				                    </div>
				                </div>
				                <div class="form-group">
				                	
				                </div>
				            </div>
				        </div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-custom-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
					<button class="btn btn-primary btn-custom-primary" type="button"><i class="fa fa-check"></i> Request Payout</button>
				</div>
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