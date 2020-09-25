@extends('mlm.layout')
@section('content')
<style type="text/css">
.newsimage
{
    width: 100%;
    height: 250px;
    min-height: 250px;
    max-height: 250px;
}
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<div class="row">
    <div class="col-md-6 col-lg-8">
        <div class="cover-holder panel panel-default panel-block panel-title-block hide">
            <div class="panel-headings clearfix">
                <img class="banner-img" src="/assets/mlm/img/banner-shop.jpg">
                <div class="shadow">
                    <img src="/assets/mlm/img/shadow.png">
                </div>
                <div class="cover-container">
                    <div class="cover-img hide">
                        
                    </div>
                    <div class="cover-text">
                        <div class="name">@if(isset($content['company_name']))  {{$content['company_name']}} @endif</div>
                        <div class="date hide">This account is a member since June 03, 2015 - 11:09 PM</div>
                        <div class="email"><span class="hide">>@if(isset($content['company_email']))  {{$content['company_email']}} @endif</span><hr></div>

                    </div>
                </div>
            </div>
        </div>
        <center class="hide"><img src="{{isset($content['company_logo']) ? $content['company_logo'] : '/assets/mlm/img/pic-shop.jpg'}}" width="200px" style="margin-top: -11px !important; height: 100%"></center>
        
        <div class="info-box bg-aqua hide">
	        <span class="info-box-icon" style="width: 40% !important;"><img  src="{{isset($content['company_logo']) ? $content['company_logo'] : '/assets/mlm/img/pic-shop.jpg'}}" alt="User Avatar" style="margin-top: -11px !important; height: 100%; width: 100%"></span>

	        <div class="info-box-content">
	        <span class="info-box-text"></span>
	          <span class="info-box-number"><center>CONGRATULATIONS!  YOU CAN NOW SEE ALL YOUR INCOME REPORTS HERE!</center></span>

	          <div class="progress">
	            <div class="progress-bar" style="width: 100%"></div>
	          </div>
	        </div>
	      </div>


          <div class="col-md-12">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header" style="background-position: center;
    background-size: contain;
    background-repeat: no-repeat;
    background-image: url('{{isset($content['company_logo']) ? $content['company_logo'] : '/assets/mlm/img/pic-shop.jpg'}}');">
                </div>

                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-12 border-right">
                      <div class="description-block">
                        <h5 class="description-header">CONGRATULATIONS!</h5>
                        <span class="description-text">YOU CAN NOW SEE ALL YOUR INCOME REPORTS HERE!</span>
                      </div>
                      <!-- /.description-block -->
                    </div>
                  </div>
                  <!-- /.row -->
                </div>
              </div>
              <!-- /.widget-user -->
            </div>


        <br />
        {!! $income !!}
    </div>
  {!! $news !!}
</div>
@endsection
@section('js')
<script type="text/javascript" src="/resources/assets/toaster/toastr.min.js"></script>
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/mlm/css/dashboard.css">
@endsection