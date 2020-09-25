@extends('mlm.layout')
@section('content')
<?php 
$data['title'] = 'Wallet';
$data['icon'] = 'icon-money';
?>
@include('mlm.header.index', $data)
<!-- <div class="row">
    <div class="col-md-12">
        <div class="panel panel-default panel-block">
            <div class="list-group">
                <div class="list-group-item" id="responsive-bordered-table">
                    <div class="form-group">
                        <div class="table-responsive"> -->
                        {!! $break_down !!}
                       <!--  </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>     -->                   
@endsection