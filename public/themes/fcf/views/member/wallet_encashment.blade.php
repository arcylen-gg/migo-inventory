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
					<div>Wallet Encashment</div>
				</div>
				<div class="sub">In this tab you can request/view encashment history. </div>
			</div>
		</div>
		<div class="right">
			<div class="text-right">
				<button type="button" class="btn btn-default"><i class="fa fa-bank"></i> PAYOUT METHOD</button>
				<button type="button" class="btn btn-primary request-payout"><i class="fa fa-credit-card"></i> REQUEST PAYOUT</button>
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
						<th class="text-left">Release Date</th>
						<th class="text-center" width="200px">Method</th>
						<th class="text-center" width="200px">Amount</th>
						<th class="text-left" width="200px">Tax</th>
						<th class="text-right" width="200px">Sub Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-left">October 12, 2017</td>
						<td class="text-center">REQUESTED (EON)</td>
						<td class="text-center"><b>PHP 15,000.00</b></td>
						<td class="text-left">10% (PHP 1,500.00)</td>
						<td class="text-right"><a href='javascript:'><b>PHP 13,500.00</b></a></td>
					</tr>
					<tr>
						<td class="text-left">October 17, 2017</td>
						<td class="text-center">WALLET PURCHASE</td>
						<td class="text-center"><b>PHP 1,000.00</b></td>
						<td class="text-left">12% (PHP 120.00)</td>
						<td class="text-right"><a href='javascript:'><b>PHP 880.00</b></a></td>
					</tr>
				</tbody>
				<tfoot style="background-color: #f3f3f3; font-size: 16px;">
					<tr>
						<td class="text-right"></td>
						<td class="text-center"></td>
						<td class="text-center"><b>PHP 16,000.00</b></td>
						<td class="text-right"></td>
						<td class="text-right"><b>PHP 14,380.00</b></td>
					</tr>
				</tfoot>
			</table>
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
					<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
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