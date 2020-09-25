@extends('member.layout')
@section('content')
<link rel="stylesheet" type="text/css" href="public/member/css/table">
<script src ="public/member/js/table"></script>
<form method="post" action="/member/page/partners/insert">
   {{csrf_field()}}
   <div class="panel panel-default panel-block panel-title-block" id="top">
      <div class="panel-heading">
         <div>
            <i class="fa fa-tags"></i>
            <h1>
               <span class="page-title"><i class="fa fa-angle-double-right"></i></span>
            </h1> 
            <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Submit</button>
         </div>
      </div>
   </div class="tab-content">
   <div class="panel panel-default panel-block panel-title-block panel-gray ">
      <ul class="nav nav-tabs">
         <li class="active menu1"><a href="#partners_form">Partner's Form</a></li>
         <li class="menu2"> <a href="#table">Partner's View</a></li>
      </ul>
      <div>
         <div class="tab-content">
            <div id="partners_form" class="tab-pane fade in active">
                <form method="post" action="/member/page/partners/insert">
                 <div class="clearfix" style="padding: 30px">
                    <div class="col-md-6" style="margin-bottom: 5px;">
                       <label>Company Name</label>  
                       <div>
                          <div class="match-height">
                             <input type="text" name="company_name" class="form-control" value="">
                          </div>
                       </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 5px;">
                       <label>Company Owner Name</label>
                       <div>
                          <div class="match-height">
                             <input type="text" name="company_owner_name"  class="form-control" value="">
                          </div>
                       </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 5px;">
                       <label>Shop ID</label>
                       <div>
                          <div class="match-height">
                             <input type="text" name="shop_id"  class="form-control" value="">
                          </div>
                       </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 5px;">
                       <label>Company Contact Number</label>
                       <div>
                          <div class="match-height">
                             <input type="text" name="company_number" class="form-control" value="">
                          </div>
                       </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 5px;">
                       <label>Company Address</label>
                       <div>
                          <div class="match-height">
                             <input type="text" name="company_address" class="form-control" value="">
                          </div>
                       </div>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 5px;">
                       <label>Company Location</label>
                       <div>
                          <div class="match-height">
                             <input type="text" name="company_branch" class="form-control" value="">
                          </div>
                       </div>
                    </div>
                    <div class="clearfix" style="padding: 30px">
                       <div class="col-md-6" style="margin-bottom: 5px;">
                          <label>Company Logo</label>
                          <div class="match-height">
                             <input class="image-value" key="company_logo" type="hidden" name="company_logo" value="{{ isset($company_info['company_logo']) ? $company_info['company_logo']->value : '' }}">
                             <div class="gallery-list image-gallery image-gallery-single" key="company_logo">
                                @if(isset($company_info['company_logo']))
                                <div>
                                   <div class="img-holder">
                                      <img class="img-responsive" src="{{ $company_info['company_logo']->value }}">
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
                </form>
            </div>
         </div>
      </div>
      </div>
   </div>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>tinymce.init({
selector:'.tinymce',
plugins: "lists",
menubar: false,
toolbar: "numlist bullist bold italic underline strikethrough"
});</script>



@endsection