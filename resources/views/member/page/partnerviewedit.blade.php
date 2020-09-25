@extends('member.layout')
@section('content')


<form method="post" action="/member/page/partner/submit_edit/{{ $company_info->company_id }}" >
	<div class="panel panel-default panel-block panel-title-block" id="top">
		<div class="panel-heading">
			<div>
				<i class="fa fa-tags"></i>
				<h1>
				<span class="page-title">Company <i class="fa fa-angle-double-right"></i> Update Company Information</span>
				<small>
				Note: You cannot EDIT company LOGO!
				</small>
				</h1>
				<button type="submit" class="panel-buttons btn btn-custom-primary pull-right">UPDATE</button>
			</div>
		</div>
	</div>
	<!-- NO PRODUCT YET -->
	<div class="panel panel-default panel-block panel-title-block panel-gray ">
		<div class="panel panel-default panel-block panel-title-block panel-gray" style="margin-bottom: 0;">
			<div class="tab-content">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div id="insert" class="tab-pane fade in active">
				   <form method="post" action="/member/page/partner/insert">
				  
				   {{csrf_field()}}
					<div class="clearfix" style="padding: 30px">
						<div class="col-md-6" style="margin-bottom: 5px;">
							<label>Company Name</label>
							<div>
								<div class="match-height">
									<input type="text" name="company_name" class="form-control" value="{{ $company_info->company_name }}">
								</div>
							</div>
						</div>
						<div class="col-md-6" style="margin-bottom: 5px;">
							<label>Company Owner Name</label>
							<div>
								<div class="match-height">
									<input type="text" name="company_owner_name"  class="form-control" value="{{ $company_info->company_owner }}">
								</div>
							</div>
						</div>
						
						<div class="col-md-6" style="margin-bottom: 5px;">
							<label>Company Contact Number</label>
							<div>
								<div class="match-height">
									<input type="text" name="company_contact_number" class="form-control" value="{{ $company_info->company_contactnumber }}">
								</div>
							</div>
						</div>
						<div class="col-md-6" style="margin-bottom: 5px;">
							<label>Company Address</label>
							<div>
								<div class="match-height">
									<input type="text" name="company_address" class="form-control" value="{{ $company_info->company_address }}">
								</div>
							</div>

						</div>
						<div class="col-md-6" style="margin-bottom: 5px;">
							<label>Company Location</label>
							<div>
								<div class="match-height">
									<input type="text" name="company_location" class="form-control" value="{{ $company_info->company_location }}">
								</div>
							</div>
						</div>
						<div class="col-md-6" style="margin-bottom: 5px;"></div>
						<div class="col-md-12">
							<div class="col-md-6" style="margin-bottom: 5px;">
								<label>Company Logo</label>
								<div class="match-height">
									<input type="hidden" name="company_logo" value="{{ $company_info->company_logo}}">
									<input class="image-value" key="company_logo" type="hidden" name="company_logo" value="{{ $company_info->company_logo }}">
									<div class="gallery-list image-gallery image-gallery-single" key="company_logo">
										@if($company_info->company_logo)
										<div>
											<div class="img-holder">
												<img class="img-responsive" src="{{ $company_info->company_logo}}">
											</div>
										</div>
										@else
										<div class="empty-notify"><i class="fa fa-image"></i> No Image Yet</div>
										@endif
									</div>
								</div>
							</div>
							<div class="col-md-6" style="margin-bottom: 5px;">
								<label>Company Brochure <small>(Minimum image width : 980px)</small></label>
								<div class="match-height">
									<input type="hidden" name="company_brochure" value="{{ $company_info->company_brochure}}">
									<input class="image-value" key="company_brochure" type="hidden" name="company_brochure" value="{{ $company_info->company_brochure }}">
									<div class="gallery-list image-gallery image-gallery-single" key="company_brochure">
										@if($company_info->company_brochure)
										<div>
											<div class="img-holder">
												<img class="img-responsive" src="{{ $company_info->company_brochure}}">
											</div>
										</div>
										@else
										<div class="empty-notify"><i class="fa fa-image"></i> No Image Yet</div>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
 </form>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/page_content.css">
<link rel="stylesheet" type="text/css" href="/assets/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="/assets/slick/slick-theme.css">
<style type="text/css">
.slick-no-slide .slick-track {
width: 100% !important;
text-align: center;
transform: translate3d(0px, 0px, 0px) !important;
}
.slick-no-slide .slick-slide {
float: none;
display: inline-block;
}
.slick-no-slide .slick-list {
padding: 0;
}
.mce-notification-warning
{
display: none;
}
.slick-list
{
height: auto !important;
}
</style>
@if(isset($job_resume))
<style type="text/css">
</style>
@endif
@endsection
@section('script')
<script type="text/javascript" src="/assets/slick/slick.js"></script>
<script type="text/javascript" src="/assets/member/js/page_content.js"></script>
<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>
<script>tinymce.init({
selector:'.tinymce',
plugins: "lists",
menubar: false,
toolbar: "numlist bullist bold italic underline strikethrough"
});</script>
@endsection