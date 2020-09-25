<style type="text/css">
	body
	{
		background-image: url("/assets/government_forms/hdmf.jpg");
		background-repeat: no-repeat;
		background-position: center center;
	}
	.employee-id-number
	{
		margin-top: 30px;
		right: 30px;
	}
</style>


<div>
	<div class="employer-id-number" style="padding-top: 87px; padding-left: 505px;">123087</div>
	<div style="padding-top: 47px; padding-left: 25px;">
		<div class="employer-company-name">DIGIMA WEB, SOLUTIONS INC</div>
	</div>
	<div style="padding-top: 25px; padding-left: 35px;">
		<div class="unit-room-number" style="float: left; width: 80px;">252</div>
		<div class="building-name" style="float: left; width: 300px; text-align: center;">CHATTEU</div>
		<div class="phase-number" style="float: left; width: 200px;">#18</div>
		<div class="street" style="float: left; width: 100px; text-align: right;">Marcos St.</div>
	</div>
	<div style="padding-top: 18px; padding-left: 7px; font-size: 12px;">
		<div class="subdivision" style="float: left; width: 100px; text-align: left;">VICENTE</div>
		<div class="barangay" style="float: left; width: 110px; text-align: left;">POBLACION </div>
		<div class="municipality" style="float: left; width: 190px; text-align: left;">PANDI</div>
		<div class="province" style="float: left; width: 235px; text-align: left;">BULACAN</div>
		<div class="province" style="float: left; width: 70px; text-align: left;">3014</div>
	</div>

	<div style="height: 47px;"></div>

	@foreach($_contribution as $contribution)
	<div style="padding-top: 5px; padding-left: 1px; font-size: 9px;">
		<div class="mid-number" style="float: left; width: 53px; text-align: center;">{{ $contribution->hdmf_number == "" ? "N/A" : substr($contribution->hdmf_number, 0, 7)  }}</div>
		<div class="account-number" style="float: left; width: 57px; text-align: center;">1125</div>
		<div class="membership-program" style="float: left; width: 57px; text-align: center; ">1125</div>
		<div class="last-name" style="float: left; width: 85px; text-align: center;">{{ $contribution->last_name }}</div>
		<div class="first-name" style="float: left; width: 85px; text-align: center;">{{ $contribution->first_name }}</div>
		<div class="name-extension" style="float: left; width: 40px; text-align: center;">{{ $contribution->name_extension }}</div>
		<div class="middle-name" style="float: left; width: 57px; text-align: center;">{{ $contribution->middle_name }}</div>
		<div class="period-covered" style="float: left; width: 53px; text-align: center;">02/2016</div>
		<div class="monthly-compensation" style="float: left; width: 65px; text-align: center;">100</div>
		<div class="ee-share" style="float: left; width: 43px; text-align: center;">{{ $contribution->ee_share }}</div>
		<div class="er-share" style="float: left; width: 40px; text-align: center;">{{ $contribution->er_share }}</div>
		<div class="er-share" style="float: left; width: 40px; text-align: center;">200</div>
		<div class="er-share" style="float: left; width: 40px; text-align: center;"></div>
	</div>
	@endforeach
</div>