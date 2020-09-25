@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Package</span>
                <small>
                    The customer can create slot using different kinds of membership.
                </small>
            </h1>
            <a href="/member/mlm/membership/add" class="panel-buttons btn btn-primary pull-right">Add Membership</a>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->

<!--<div class="row">-->
<!--    <div class="col-md-12 text-center">-->
<!--        <div class="trial-warning clearfix">-->
<!--            <div class="no-product-title">Add your first product</div>-->
<!--            <div class="no-product-subtitle">You’re just a few steps away from receiving your first order.</div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-star"></i> Active Membership</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-trash"></i> Trashed Membership</a></li>
    </ul>
    
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-hover table-compress;">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Membership Name</th>
                            <th>Membership Price</th>
                            <th>Membership Rank</th>
                            <th>Inclusive Products</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: bold;">Platinum</td>
                            <td>PHP 5,500.00</td>
                            <td>1st</td>
                            <td class="text-left"><a href=""><span style="color: red;">No Package Yet</a></span></td>
                            <td class="text-center"><input type="checkbox" checked="checked" disabled="disabled" name=""/></td>
                            <td>
                                <a href="">EDIT</a> |
                                <a href="">DELETE</a>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;">Gold</td>
                            <td>PHP 3,500.00</td>
                            <td>2nd</td>
                            <td class="text-left"><a href="">2 Package</a></td>
                            <td class="text-center"><input type="checkbox" checked="checked" disabled="disabled" name=""/></td>
                            <td>
                                <a href="">EDIT</a> |
                                <a href="">DELETE</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/product.css">
<link rel="stylesheet" type="text/css" href="/assets/member/css/order.css">
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/global_view.js"></script>
@endsection