<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Authorized Access">
	<a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
		<i class="fa fa-fw fa-user-secret"></i>
		<span class="nav-link-text">Approver Access</span>
	</a>
	<ul class="sidenav-second-level collapse" id="collapseComponents">
		@if($approver_leave != null)
		<li>
			<a class="nav-link" href="authorized_access_leave">Leave</a>
		</li>
		@endif
		@if($approver_ot != null)
		<li>
			<a class="nav-link" href="authorized_access_over_time">Overtime</a>
		</li>
		@endif
		@if($approver_ob != null)
		<li>
			<a class="nav-link" href="authorized_access_official_business">OB</a>
		</li>
		@endif
		@if($approver_rfp != null)
		<li>
			<a class="nav-link" href="authorized_access_request_for_refund">Request For Refund</a>
		</li>
		@endif
	</ul>
</li>