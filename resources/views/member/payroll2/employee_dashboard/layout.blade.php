<!DOCTYPE html>

<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <meta name="description" content="">
	    <meta name="author" content="">
	    <title>DIGIMAHOUSE</title>
	    <!-- JAMES -->
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
	    <!-- Bootstrap core CSS-->
	    <link href="/assets/employee/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	    <!-- Custom fonts for this template-->
	    <link href="assets/employee/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	    <!-- Page level plugin CSS-->
	    <link href="/assets/employee/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
	    <!-- Custom styles for this template-->
	    <link href="/assets/employee/css/sb-admin.css" rel="stylesheet">
	    <link href="/assets/employee/css/employee_profile.css" rel="stylesheet">
	    <link rel="stylesheet" type="text/css" href="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.css">
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	    <!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> -->

	    <style>
	    	.pagination {
	    	  display: inline-block;
	    	  padding-left: 0;
	    	  margin: 20px 0;
	    	  border-radius: 4px;
	    	}
	    	.pagination > li {
	    	  display: inline;
	    	}
	    	.pagination > li > a,
	    	.pagination > li > span {
	    	  position: relative;
	    	  float: left;
	    	  padding: 6px 12px;
	    	  margin-left: -1px;
	    	  line-height: 1.42857143;
	    	  color: #337ab7;
	    	  text-decoration: none;
	    	  background-color: #fff;
	    	  border: 1px solid #ddd;
	    	}
	    	.pagination > li:first-child > a,
	    	.pagination > li:first-child > span {
	    	  margin-left: 0;
	    	  border-top-left-radius: 4px;
	    	  border-bottom-left-radius: 4px;
	    	}
	    	.pagination > li:last-child > a,
	    	.pagination > li:last-child > span {
	    	  border-top-right-radius: 4px;
	    	  border-bottom-right-radius: 4px;
	    	}
	    	.pagination > li > a:hover,
	    	.pagination > li > span:hover,
	    	.pagination > li > a:focus,
	    	.pagination > li > span:focus {
	    	  z-index: 2;
	    	  color: #23527c;
	    	  background-color: #eee;
	    	  border-color: #ddd;
	    	}
	    	.pagination > .active > a,
	    	.pagination > .active > span,
	    	.pagination > .active > a:hover,
	    	.pagination > .active > span:hover,
	    	.pagination > .active > a:focus,
	    	.pagination > .active > span:focus {
	    	  z-index: 3;
	    	  color: #fff;
	    	  cursor: default;
	    	  background-color: #337ab7;
	    	  border-color: #337ab7;
	    	}
	    	.pagination > .disabled > span,
	    	.pagination > .disabled > span:hover,
	    	.pagination > .disabled > span:focus,
	    	.pagination > .disabled > a,
	    	.pagination > .disabled > a:hover,
	    	.pagination > .disabled > a:focus {
	    	  color: #777;
	    	  cursor: not-allowed;
	    	  background-color: #fff;
	    	  border-color: #ddd;
	    	}
	    	.pagination-lg > li > a,
	    	.pagination-lg > li > span {
	    	  padding: 10px 16px;
	    	  font-size: 18px;
	    	  line-height: 1.3333333;
	    	}
	    	.pagination-lg > li:first-child > a,
	    	.pagination-lg > li:first-child > span {
	    	  border-top-left-radius: 6px;
	    	  border-bottom-left-radius: 6px;
	    	}
	    	.pagination-lg > li:last-child > a,
	    	.pagination-lg > li:last-child > span {
	    	  border-top-right-radius: 6px;
	    	  border-bottom-right-radius: 6px;
	    	}
	    	.pagination-sm > li > a,
	    	.pagination-sm > li > span {
	    	  padding: 5px 10px;
	    	  font-size: 12px;
	    	  line-height: 1.5;
	    	}
	    	.pagination-sm > li:first-child > a,
	    	.pagination-sm > li:first-child > span {
	    	  border-top-left-radius: 3px;
	    	  border-bottom-left-radius: 3px;
	    	}
	    	.pagination-sm > li:last-child > a,
	    	.pagination-sm > li:last-child > span {
	    	  border-top-right-radius: 3px;
	    	  border-bottom-right-radius: 3px;
	    	}
	    	.pager {
	    	  padding-left: 0;
	    	  margin: 20px 0;
	    	  text-align: center;
	    	  list-style: none;
	    	}
	    	.pager li {
	    	  display: inline;
	    	}
	    	.pager li > a,
	    	.pager li > span {
	    	  display: inline-block;
	    	  padding: 5px 14px;
	    	  background-color: #fff;
	    	  border: 1px solid #ddd;
	    	  border-radius: 15px;
	    	}
	    	.pager li > a:hover,
	    	.pager li > a:focus {
	    	  text-decoration: none;
	    	  background-color: #eee;
	    	}
	    	.pager .next > a,
	    	.pager .next > span {
	    	  float: right;
	    	}
	    	.pager .previous > a,
	    	.pager .previous > span {
	    	  float: left;
	    	}
	    	.pager .disabled > a,
	    	.pager .disabled > a:hover,
	    	.pager .disabled > a:focus,
	    	.pager .disabled > span {
	    	  color: #777;
	    	  cursor: not-allowed;
	    	  background-color: #fff;
	    	}
	    </style>
	    @yield('css')
  	</head>
	<body class="fixed-nav sticky-footer bg-dark" id="page-top">
	<!-- Navigation-->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
		<a class="navbar-brand" href="#">DIGIMAHOUSE</a>
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
		<i class="navbar-toggler-icon" aria-hidden="true"></i>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
				
			{{ (new App\Http\Controllers\Member\PayrollEmployee\EmployeeController)->authorized_access($employee_id) }}
				{{-- <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
					<a class="nav-link" href="/employee">
						<i class="fa fa-fw fa-dashboard"></i>
						<span class="nav-link-text">Dashboard</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Profile">
					<a class="nav-link" href="/employee_profile">
						<i class="fa fa-fw fa-user"></i>
						<span class="nav-link-text">Profile</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Time Keeping">
					<a class="nav-link" href="/employee_time_keeping">
						<i class="fa fa-fw fa-table"></i>
						<span class="nav-link-text">Time Keeping</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Company Details">
					<a class="nav-link" href="/company_details">
						<i class="fa fa-fw  fa-id-card"></i>
						<span class="nav-link-text">Company Details</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Leave Management">
					<a class="nav-link" href="/employee_leave_management">
						<i class="fa fa-fw fa-calendar"></i>
						<span class="nav-link-text">Leave Management</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Overtime Management">
					<a class="nav-link" href="employee_overtime_management">
						<i class="fa fa-fw fa-clock-o"></i>
						<span class="nav-link-text">Overtime Management</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="OB Management">
					<a class="nav-link" href="employee_official_business_management">
						<i class="fa fa-fw fa-tasks"></i>
						<span class="nav-link-text">OB Management</span>
					</a>
				</li>
				<li class="nav-item" data-toggle="tooltip" data-placement="right" title="RFP">
					<a class="nav-link" href="/request_for_payment">
						<i class="fa fa-fw fa-list-alt"></i>
						<span class="nav-link-text">RFP</span>
					</a>

				</li> --}}

				{{ (new App\Http\Controllers\Member\PayrollEmployee\EmployeeController)->approver_access($employee_id) }}
				
			</ul>
			<ul class="navbar-nav sidenav-toggler">
				<li class="nav-item">
					<a class="nav-link text-center" id="sidenavToggler">
						<i class="fa fa-fw fa-angle-left"></i>
					</a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle mr-lg-2" id="messagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-fw fa-envelope"></i>
						<span class="d-lg-none">Messages
							<span class="badge badge-pill badge-primary">12 New</span>
						</span>
						<span class="indicator text-primary d-none d-lg-block">
							<i class="fa fa-fw fa-circle"></i>
						</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="messagesDropdown">
						<h6 class="dropdown-header">New Messages:</h6>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">
							<strong>David Miller</strong>
							<span class="small float-right text-muted">11:21 AM</span>
							<div class="dropdown-message small">Hey there! This new version of SB Admin is pretty awesome! These messages clip off when they reach the end of the box so they don't overflow over to the sides!</div>
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">
							<strong>Jane Smith</strong>
							<span class="small float-right text-muted">11:21 AM</span>
							<div class="dropdown-message small">I was wondering if you could meet for an appointment at 3:00 instead of 4:00. Thanks!</div>
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">
							<strong>John Doe</strong>
							<span class="small float-right text-muted">11:21 AM</span>
							<div class="dropdown-message small">I've sent the final files over to you for review. When you're able to sign off of them let me know and we can discuss distribution.</div>
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item small" href="#">View all messages</a>
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-fw fa-bell"></i>
						<span class="d-lg-none">Alerts
							<span class="badge badge-pill badge-warning">6 New</span>
						</span>
						<span class="indicator text-warning d-none d-lg-block">
							<i class="fa fa-fw fa-circle"></i>
						</span>
					</a>
					<div class="dropdown-menu" aria-labelledby="alertsDropdown">
						<h6 class="dropdown-header">New Alerts:</h6>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">
							<span class="text-success">
								<strong>
								<i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
							</span>
							<span class="small float-right text-muted">11:21 AM</span>
							<div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">
							<span class="text-danger">
								<strong>
								<i class="fa fa-long-arrow-down fa-fw"></i>Status Update</strong>
							</span>
							<span class="small float-right text-muted">11:21 AM</span>
							<div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">
							<span class="text-success">
								<strong>
								<i class="fa fa-long-arrow-up fa-fw"></i>Status Update</strong>
							</span>
							<span class="small float-right text-muted">11:21 AM</span>
							<div class="dropdown-message small">This is an automated server response message. All systems are online.</div>
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item small" href="#">View all alerts</a>
					</div>
				</li>
				<li class="nav-item">
					<form class="form-inline my-2 my-lg-0 mr-lg-2">
						<div class="input-group">
							<input class="form-control" type="text" placeholder="Search for...">
							<span class="input-group-btn">
								<button class="btn btn-primary" type="button">
								<i class="fa fa-search"></i>
								</button>
							</span>
						</div>
					</form>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="modal" data-target="#exampleModal">
					<i class="fa fa-fw fa-sign-out"></i>Logout</a>
				</li>
			</ul>
		</div>
		</nav>
		<div class="content-wrapper">
			<div class="container-fluid">
				<!-- Breadcrumbs-->
				<div class="main">
					@yield("content")
					<!-- Content Here -->
				</div>
			</div>
			<!-- /.container-fluid-->
			<!-- /.content-wrapper-->
			<footer class="sticky-footer">
				<div class="container">
					<div class="text-center">
						<small>Copyright © Your Website 2017</small>
					</div>
				</div>
			</footer>
			<!-- Scroll to Top Button-->
			<a class="scroll-to-top rounded" href="#page-top">
				<i class="fa fa-angle-up"></i>
			</a>
			<!-- Logout Modal-->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
							<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
						<div class="modal-footer">
							<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
							<a class="btn btn-primary" href="/employee_logout">Logout</a>
						</div>
					</div>
				</div>
			</div>
			<!-- Bootstrap core JavaScript-->
			<script src="/assets/employee/vendor/jquery/jquery.min.js"></script>
			<script src="/assets/employee/vendor/popper/popper.min.js"></script>
			<script src="/assets/employee/vendor/bootstrap/js/bootstrap.min.js"></script>
			<!-- Core plugin JavaScript-->
			<script src="/assets/employee/vendor/jquery-easing/jquery.easing.min.js"></script>
			<!-- Page level plugin JavaScript-->
			<script src="/assets/employee/vendor/chart.js/Chart.min.js"></script>
			<script src="/assets/employee/vendor/datatables/jquery.dataTables.js"></script>
			<script src="/assets/employee/vendor/datatables/dataTables.bootstrap4.js"></script>
			<!-- Custom scripts for all pages-->
			<script src="/assets/employee/js/sb-admin.min.js"></script>
			<!-- Custom scripts for this page-->
			<script src="/assets/employee/js/sb-admin-datatables.min.js"></script>
			<script src="/assets/employee/js/global_function.js"></script>
			<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.plugin.min.js"></script>
			<script type="text/javascript" src="/assets/external/jquery.timeentry.package-2.0.1/jquery.timeentry.min.js"></script>
			@yield('script')
			@yield('js')

			{{-- MODAL --}}
			<div id="global_modal" class="modal fade" role="dialog" >
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content modal-content-global clearfix">
					</div>
				</div>
			</div>
			<div class="multiple_global_modal_container"></div>
		</div>
	</body>
</html>