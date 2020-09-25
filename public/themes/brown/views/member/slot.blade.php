@extends("member.member_layout")
@section("member_content")
<div class="member-slot">
	<div class="main-member-header clearfix">
		<div class="left">
			<div class="icon">
				<div class="brown-icon-cubes"></div>
			</div>
			<div class="text">
				<div class="name">My Slots</div>
			</div>
		</div>
		<div class="right">
			
		</div>
	</div>
	<div class="member-slot-content">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>
							<div class="header-label">Slot</div>
							<button class="btn btn-hover">Hover Details</button>
						</th>
						<th>
							<div class="header-label">Date Created</div>
							<button class="btn btn-hover">Hover Details</button>
						</th>
						<th>
							<div class="header-label">Current Wallet</div>
							<button class="btn btn-hover">Hover Details</button>
						</th>
						<th>
							<div class="header-label">Tree Matrix</div>
							<button class="btn btn-hover">Hover Details</button>
						</th>
						<th>
							<div class="header-label">Used Slot</div>
							<button class="btn btn-hover">Hover Details</button>
						</th>
						<th>
							<div class="header-label">Default Slot</div>
							<button class="btn btn-hover">Hover Details</button>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>272842</td>
						<td>2017-06-19 10:39:18 </td>
						<td>P 99,000.00</td>
						<td><div style="background: linear-gradient(to right, rgb(145, 158, 168) 7.9872984855887%, rgb(233, 233, 233) 7.9872984855887%);" class="progress2">327 / 4094 (7.9872984855887%)</div></td>
						<td>
							<button class="active"><img src="/themes/{{ $shop_theme }}/img/used-slot.png"></button>
						</td>
						<td>
							<button class="active"><img src="/themes/{{ $shop_theme }}/img/default-slot.png"></button>
						</td>
					</tr>
					<tr>
						<td>272842</td>
						<td>2017-06-19 10:39:18 </td>
						<td>P 99,000.00</td>
						<td><div style="background: linear-gradient(to right, rgb(145, 158, 168) 7.9872984855887%, rgb(233, 233, 233) 7.9872984855887%);" class="progress2">327 / 4094 (7.9872984855887%)</div></td>
						<td>
							<button><img src="/themes/{{ $shop_theme }}/img/used-slot.png"></button>
						</td>
						<td>
							<button><img src="/themes/{{ $shop_theme }}/img/default-slot.png"></button>
						</td>
					</tr>
					<tr>
						<td>272842</td>
						<td>2017-06-19 10:39:18 </td>
						<td>P 99,000.00</td>
						<td><div style="background: linear-gradient(to right, rgb(145, 158, 168) 7.9872984855887%, rgb(233, 233, 233) 7.9872984855887%);" class="progress2">327 / 4094 (7.9872984855887%)</div></td>
						<td>
							<button><img src="/themes/{{ $shop_theme }}/img/used-slot.png"></button>
						</td>
						<td>
							<button><img src="/themes/{{ $shop_theme }}/img/default-slot.png"></button>
						</td>
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
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/slot.css">
@endsection