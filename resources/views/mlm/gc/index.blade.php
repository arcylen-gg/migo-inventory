@extends('mlm.layout')
@section('content')
@section('content')
<?php 
$data['title'] = 'Gift Certificate';
$data['sub'] = 'All Gift Certificates are shown here.';
$data['icon'] = 'icon-barcode';
?>
@include('mlm.header.index', $data)
    
@if(count($gc) >= 1)
@foreach($gc as $key => $value)
<div class="col-md-6">
  <!-- Widget: user widget style 1 -->
  <div class="box box-widget widget-user">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <div class="widget-user-header {{$value->mlm_gc_used == 0 ? 'bg-aqua-active' : 'bg-green-active'}} ">
      <h3 class="widget-user-username">@if(isset($content['company_name']))  {{$content['company_name']}} @endif</h3>
      <h5 class="widget-user-desc">GIFT CERTIFICATE</h5>
    </div>
    <div class="widget-user-image">
      <!-- <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar"> -->
    </div>
    <div class="box-footer">
      <div class="row">
        <div class="col-sm-4 border-right">
          <div class="description-block">
            <h5 class="description-header">{{$value->mlm_gc_amount}}</h5>
            <span class="description-text">Amount</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-4 border-right">
          <div class="description-block">
            <h5 class="description-header">{{$value->mlm_gc_code}}</h5>
            <span class="description-text">CODE</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-4">
          <div class="description-block">
            <h5 class="description-header">{{$value->mlm_gc_used == 0 ? 'UNUSED' : 'USED'}}</h5>
            <span class="description-text">STATUS</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
  </div>
  <!-- /.widget-user -->
</div>
@endforeach
@else
<center><h3>No Available Gc.</h3></center>
@endif

@endsection
@section('js')

@endsection