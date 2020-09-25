@extends('member.layout')
@section('content')
<form method="post">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                <span class="page-title"><i class="fa fa-angle-double-right"></i></span>
                
                </h1>
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray ">
    <table class="table table-bordered">
  <thead>
    <tr>
      <th>Company Logo</th>
      <th>Company Name</th>
      <th><center>Company Owner Name</center></th>
      <th>Company Number</th>
      <th>Company Address</th>
      <th>Company Location</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
   @foreach($company as $company)
    <tr>
      <td><img src ="{{ $company->company_logo }}" height="100" width="150"></td>
      <td>{{ $company->company_name }}</td>
      <td>{{ $company->company_owner_name }}</td>
      <td>{{ $company->company_number }}</td>
      <td>{{ $company->company_address }}</td>
      <td>{{ $company->company_branch }}</td>
      <td>
          <div class="dropdown">
              <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
                  <span class="caret"></span></button>
                  <ul class="dropdown-menu dropdown-menu-custom">
                    <li>
                    <a href="/member/page/partners/getinfo?comp_id={{ $company->company_id }}" size="lg">Edit</a>
                    <a href="/member/page/partners/deletecompanyinfo?comp_id={{ $company->company_id }}" size="lg">Delete</a>
                  </li>
              </ul>
          </div></td>
      </tr>
  </tbody>
  @endforeach
</table>
    </div>
</form>
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
<script>tinymce.init({
selector:'.tinymce',
plugins: "lists",
menubar: false,
toolbar: "numlist bullist bold italic underline strikethrough"
});</script>
@endsection