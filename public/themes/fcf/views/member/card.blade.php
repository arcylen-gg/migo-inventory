<div class="card">
	<div class="row">
		<div class="col-md-4 text-center">
			<div class="profile-image">
				<img src="{{ $sponsor_profile_image }}">
			</div>
		</div>
		<div class="col-md-8">
			<div class="info">
				<div class="name">{{ $sponsor_customer->first_name }} {{ $sponsor_customer->last_name }}</div>
				<div class="email">{{ $sponsor_customer->email }}</div>
				<div class="slotno">SLOT NO. {{ $sponsor->slot_no }}</div>
			</div>
		</div>
	</div>
</div>