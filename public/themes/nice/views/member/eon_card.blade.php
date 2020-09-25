@extends("member.member_layout")
@section("member_content")
<div class="eon-card">
	<div class="main-member-header clearfix">
		<div class="left">
			<div class="icon">
				<div class="brown-icon-credit-card-1"></div>
			</div>
			<div class="text">
				<div class="name">Eon Card Registration</div>
			</div>
		</div>
		<div class="right">
			
		</div>
	</div>
	<div class="eon-card-content">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Slot</th>
						<th>Account Name</th>
						<th>Account Number</th>
						<th>Card No.</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>272842</td>
						<td><input class="form-control input-sm" type="text" name="" value="Brown and Proud Lorem"></td>
						<td><input class="form-control input-sm" type="text" name="" value="000000000000"></td>
						<td><input class="form-control input-sm" type="text" name="" value="000000000000"></td>
						<td><button class="btn btn-brown"><img src="/themes/{{ $shop_theme }}/img/white-check.png"></button></td>
					</tr>
					<tr>
						<td>272842</td>
						<td><input class="form-control input-sm" type="text" name="" value="Brown and Proud Lorem"></td>
						<td><input class="form-control input-sm" type="text" name="" value="000000000000"></td>
						<td><input class="form-control input-sm" type="text" name="" value="000000000000"></td>
						<td><button class="btn btn-brown"><img src="/themes/{{ $shop_theme }}/img/white-check.png"></button></td>
					</tr>
					<tr>
						<td>272842</td>
						<td><input class="form-control input-sm" type="text" name="" value="Brown and Proud Lorem"></td>
						<td><input class="form-control input-sm" type="text" name="" value="000000000000"></td>
						<td><input class="form-control input-sm" type="text" name="" value="000000000000"></td>
						<td><button class="btn btn-brown"><img src="/themes/{{ $shop_theme }}/img/white-check.png"></button></td>
					</tr>
					<tr>
						<td>272842</td>
						<td><input class="form-control input-sm" type="text" name="" value="Brown and Proud Lorem"></td>
						<td><input class="form-control input-sm" type="text" name="" value="000000000000"></td>
						<td><input class="form-control input-sm" type="text" name="" value="000000000000"></td>
						<td><button class="btn btn-brown"><img src="/themes/{{ $shop_theme }}/img/white-check.png"></button></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
@section("member_script")
@endsection
@section("member_css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/eon_card.css">
@endsection