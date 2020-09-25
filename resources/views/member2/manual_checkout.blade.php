<div class="container">
	<div class="wrapper-1">
		<div class="texts-1">Transaction Reference Number for this transaction is</div>
		<div class="order">{{ str_pad($transaction->transaction_number, 8, '0', STR_PAD_LEFT) }}</div>
		<div class="amount">{{ $transaction_total }}</div>
		<div class="texts-2">E-Mail for this link has been sent to your e-mail address.<br>You can click below to upload your proof of payment.</div>

		<div class="file-container">
			<form method="post" class="upload-proof-of-payment" enctype="multipart/form-data" autocomplete="off">
				{{ csrf_field() }}
				<div class="form-group text-left">
					<label>Proof of Payment (Image)</label>
					<input class="payment-proof" type="file" name="proofupload" required>
				</div>
				<div class="form-group text-left" style="margin-top: 25px; margin-bottom: 0;">
					<label>Payment Details:</label>
				</div>
				@if($method_name == "PAYMAYA")
					<div class="form-group">
						<div class='input-group date datetime-picker'>
		                    <input name="date_and_time" type='text' class="form-control to-pick" placeholder="Date and Time" />
		                    <span class="input-group-addon to-click">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                    </span>
		                </div>
					</div>
					<div class="form-group">
						<input name="reference_number" class="form-control" type="text" step="any" placeholder="Reference Number">
					</div>				
					<div class="form-group">
						<input name="amount" class="form-control" type="number" step="any" placeholder="Amount">
					</div>
				@elseif($method_name == "COINS.PH")
					<div class="form-group">
						<div class='input-group date datetime-picker'>
		                    <input name="date_and_time" type='text' class="form-control to-pick" placeholder="Date and Time" />
		                    <span class="input-group-addon to-click">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                    </span>
		                </div>
					</div>
					<div class="form-group">
						<input name="description" class="form-control" type="text" placeholder="Description">
					</div>				
					<div class="form-group">
						<input name="amount" class="form-control" type="number" step="any" placeholder="Amount">
					</div>
				@else
					<div class="form-group">
						<div class='input-group date datetime-picker'>
		                    <input name="date_and_time" type='text' class="form-control to-pick" placeholder="Date and Time" />
		                    <span class="input-group-addon to-click">
		                        <span class="glyphicon glyphicon-calendar"></span>
		                    </span>
		                </div>
					</div>
					<div class="form-group">
						<input name="reference_number" class="form-control" type="text" step="any" placeholder="Reference Number">
					</div>
					
					<div class="form-group">
						<input name="sender_name" class="form-control" type="text" placeholder="Sender's Name">
					</div>					
					<div class="form-group">
						<input name="amount" class="form-control" type="number" step="any" placeholder="Amount">
					</div>
				@endif
				<button class="btn btn-primary btn-submit">Submit</button>
			</form>
		</div>
	</div>
</div>

<div class="wrapper-2">
	<div class="container-fluid">
		<div class="row clearfix">
			<div class="col-md-6">
				<div class="account-list-container">
					<div class="text-header">ACCOUNT LIST</div>
					<div class="content">{!! $api->api_client_id !!}</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="step-by-step-container">
					<div class="text-header">STEP BY STEP INSTRUCTION</div>
					<div class="content">{!! $api->api_secret_id !!}</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- BOOTSTRAP -->
<link rel="stylesheet" href="/assets/initializr/css/bootstrap.min.css">
<link rel="stylesheet" href="/assets/initializr/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="/assets/initializr/css/datetimepicker.css">
<link rel="stylesheet" type="text/css" href="/assets/member/plugin/toaster/toastr.css">
<!-- GOOGLE FONT -->
<link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/member/css/manual_checkout.css">

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
<script type="text/javascript" src="/assets/initializr/js/vendor/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/initializr/js/vendor/datetimepicker.js"></script>
<script type="text/javascript" src="/assets/member/plugin/toaster/toastr.min.js"></script>
<script type="text/javascript">
	$(document).ready(function()
	{
		$("input").attr("required", true);
		$(".payment-proof").change(function()
		{
			// $(".upload-proof-of-payment").submit();
			var input = this.files[0].type.split("/");
			if(input[0] != 'image')
			{
				toastr.error("Error. File should be an image");
				this.value="";
			}
		});

        $('.datetime-picker').datetimepicker();
        $('.to-pick').click(function()
        {
        	$('.to-click').trigger('click');
        });
	});
</script>
<script type="text/javascript">
	@if(Session::get("response")=='error')
	toastr.error("You already send a proof");
	@endif
</script>
