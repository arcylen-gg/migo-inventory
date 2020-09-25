@extends('member.layout')
@section('content')
<input type="hidden" value="{{csrf_token()}}" id="_token" name="_token">
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-user-secret"></i>
            <h1>
                <span class="page-title"><span class="color-gray">Vendor Information</span>
                <small>
                Vendors are the people whom you purchase products.
                </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-star"></i> Active Vendors</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-trash"></i> Inactive Vendors</a></li>
    </ul>
    
    <div class="search-filter-box">
        <div class="col-md-3" style="padding: 10px">
            <select class="form-control">
                <option>All Vendors</option>
                <option>Vendors with Open Balances</option>
                <option>Vendors with No Balance</option>
            </select>
        </div>
        <div class="col-md-4 col-md-offset-5" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search by Vendor Name" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    
    <div class="panel-body">
        <table style="table-layout: fixed;" class="table table-hover table-condensed table-bordered table-sale-month">
            <thead>
                <tr>
                    <th class="text-left">Name</th>
                    <th class="text-left">Phone</th>
                    <th class="text-left">Email</th>
                    <th class="text-center">Balance Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr class="cursor-pointer">
                    <td class="text-left">Guillermo Tabligan</td>
                    <td class="text-left">(63) 977809113</td>
                    <td class="text-left">cio@digimaweb.solutions</td>
                    <td class="text-center" style="color: red;">PHP 1,500.00</td>
                    <td class="text-center">
                        <!-- ACTION BUTTON -->
                        <div class="btn-group">
                          <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-custom">
                            <li><a href="/member/vendor/create_bill">Create Bill</a></li>
                            <li><a href="/member/vendor/write_check">Write Check</li>
                            <li><a href="/member/vendor/purchase_order">Create Purchase Order</a></li>
                            <li><a href="#">Make Inactive</a></li>
                          </ul>
                        </div>
                    </td>
                </tr>
                <tr class="cursor-pointer">
                    <td class="text-left">Raymond Fajardo</td>
                    <td class="text-left">(63) 916801584</td>
                    <td class="text-left">raymond.fajardo@digimaweb.solutions</td>
                    <td class="text-center">PHP 0.00</td>
                    <td class="text-center">
                        <!-- ACTION BUTTON -->
                        <div class="btn-group">
                          <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Action <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-custom">
                            <li><a href="/member/vendor/create_bill">Create Bill</a></li>
                            <li><a href="/member/vendor/write_check">Write Check</li>
                            <li><a href="/member/vendor/purchase_order">Create Purchase Order</a></li>
                            <li><a href="#">Make Inactive</a></li>
                          </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection