@extends('mlm.layout')
@section('content')
<?php 
$data['title'] = 'Request Wallet Encashment';
$data['sub'] = '';
$data['icon'] = 'icon-money';
$data['button'][0] = '<a href="/mlm/encashment" class="small-box-footer pull-right">Back<i class="fa fa-arrow-circle-left"></i></a>';
?>  
@include('mlm.header.index', $data) 
         
@endsection
@section('script')

@endsection