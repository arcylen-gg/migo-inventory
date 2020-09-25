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
					<div>Wallet Refill</div>
				</div>
				<div class="sub">In this tab you can request/view wallet refill history. </div>
			</div>
		</div>
		<div class="right">
			<div class="text-right">
				<!-- <button type="button" class="btn btn-default popup" link="/members/payout-setting" size="md"><i class="fa fa-bank"></i> PAYOUT METHOD</button> -->
				<button type="button" class="btn btn-primary popup" link="/members/wallet-refill-request" size="md"><i class="fa fa-money"></i> REQUEST REFILL</button>
			</div>
		</div>
	</div>
	<div class="wallet-encashment-content">
		<div class="col-md-12">
			<div class="col-md-4 title" style="padding: 10px;">
				<label>Slot no</label>
				<select name="slot" class="form-control slot-owner">
				@foreach($slot_owner as $owner)
				<option value="{{$owner->slot_no}}">{{$owner->slot_no}}</option>
				@endforeach
				</select>
			</div>
		</div>
	<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
		
		<ul class="nav nav-tabs">
	        <li class="active cursor-pointer change-tab approve-tab" mode="1"><a class="cursor-pointer"><i class="fa fa-check"></i> Approved</a></li>
	        <li class="change-tab pending-tab cursor-pointer" mode="0"><a class="cursor-pointer"><i class="fa fa-progress"></i> Pending</a></li>
	        <li class="change-tab pending-tab cursor-pointer" mode="2"><a class="cursor-pointer"><i class="fa fa-close"></i> Rejected</a></li>
	    </ul>
		<div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive load-table-here">
                        
                    </div>
                </div>
            </div>
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
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/wallet_refill.js"></script>
<script type="text/javascript">
	@if(Session::get("response")=='success')
	toastr.success("Wallet request submitted");
	@elseif(Session::get("response")=='error')
	toastr.error("Error sending request");
	@elseif(Session::get("response")=='invalid_slot')
	toastr.error("The slot you are using does not belong to you. Please reload the page and try again");
	@elseif(Session::get("response")=='success_upload')
	toastr.success("Attachment uploaded");
	@elseif(Session::get("response")=='error_upload')
	toastr.error("Error uploading file");
	@endif
</script>
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/wallet_encashment.css">
@endsection