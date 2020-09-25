@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block panel-gray" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-star"></i>
            <h1>
                <span class="page-title">Wishlist</span>
                <small>
                    List of wishlist.
                </small>
            </h1>
            <div class="text-right">
                
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#products"><i class="fa fa-credit-card" aria-hidden="true"></i>&nbsp;Products</a></li>
      <li><a data-toggle="tab" href="#customers"><i class="fa fa-users" aria-hidden="true"></i>&nbsp;Customers</a></li>
    </ul>
    <div class="tab-content">
        <div id="products" class="tab-pane fade in active">
            {!! $product !!}
        </div>
        <div id="customers" class="tab-pane fade in">
            {!! $customer !!}
        </div>
    </div>
</div>
@endsection
@section("script")
<script type="text/javascript" src="/assets/member/js/wishlist.js"></script>
@endsection