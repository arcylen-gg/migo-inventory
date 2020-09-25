@extends('mlm.layout')
@section('content')
<?php 
$data['title'] = 'Use Product Code';
$data['sub'] = '';
$data['icon'] = 'fa fa-code';
?>  
@include('mlm.header.index', $data) 
<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Orders</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Plan</th>
                    <th>Bonus/Points</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($membership_points as $key => $value)
                  <tr>
                    <td>{{$key}}</td>
                    <td>{{$value}}</td>
                  </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
              <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div>
            <!-- /.box-footer -->
          </div>
@endsection
@section('script')

@endsection