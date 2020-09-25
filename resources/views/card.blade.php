<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="{{Request::input('pdf') == 'true' ? public_path() : ''}}/assets/card/style.css">
		<link href="https://fonts.googleapis.com/css?family=Poppins:300,600" rel="stylesheet">  
	</head>
	<body>
		<div class="containers" style="background-color: transparent; background-image: url('{{Request::input('pdf') == 'true' ? URL::to('/') : ''}}/assets/card/images/BG-{{ $color }}.jpg') !important;">
			<div class="top-container">
				<span class="website">{{ URL::to('/') }}</span>
				<div class="logo"><img src="{{Request::input('pdf') == 'true' ? public_path() : ''}}/assets/card/images/philtech-logo.png"></div>
			</div>
			<div class="mid-container"><img src="{{Request::input('pdf') == 'true' ? public_path() : ''}}/assets/card/images/{{ $color }}-logo.png"></div>
			<div class="bottom-container">
				<div class="member">
					<div class="member-name" >{{ $name }}</div>
					<div class="member-label">MEMBER</div>
				</div>
				<div class="barcode">
					<div class="barcodeimg" style="background-color: #fff; padding: 7.5px 0;"><img src="{{Request::input('pdf') == 'true' ? URL::to('/') : ''}}/barcode?text={{ $membership_code }}&size=20"></div>
					<div class="barcodetxt" style="margin-top: -12.5px;">
						<span>Membership No.</span>
						<span>{{ $membership_code }}</span>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
