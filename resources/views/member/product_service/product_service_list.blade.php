@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Service List</span>
                <small>
                    These are the list of services rendered by the company.
                </small>
            </h1>
            <a href="javascript:" class="panel-buttons btn btn-default pull-right">Export Slots</a>
        </div>
    </div>
</div>

<!-- NO PRODUCT YET -->
<div class="panel panel-default panel-block panel-title-block panel-gray ">
    <ul class="nav nav-tabs">
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-star"></i> Active Slots</a></li>
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab"><i class="fa fa-trash"></i> Inactive Slots</a></li>
    </ul>
    
    <div class="search-filter-box">
        <div class="col-md-2" style="padding: 10px">
            <select class="form-control">
                <option>All Membership</option>
                <option>Silver</option>
                <option>Gold</option>
                <option>Platinum</option>
            </select>
        </div>
        <div class="col-md-2" style="padding: 10px">
            <select class="form-control">
                <option>All Type</option>
                <option>Paid Slot</option>
                <option>Free Slot</option>
                <option>CD Slot</option>
            </select>
        </div>
        <div class="col-md-4 col-md-offset-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search by Customer or Slot ID" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div>
    
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="table-responsive">
                <table class="table table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th>Slot ID</th>
                            <th class="text-center">Membership</th>
                            <th class="text-center">Owner</th>
                            <th class="text-center">Date Created</th>
                            <th class="text-center">Sponsored By</th>
                            <th class="text-center">Total Earnings</th>
                            <th class="text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2314</td>
                            <td class="text-center">Platinum (PS)</td>
                            <td class="text-center"><a href="javascript:">Guillermo Tabligan</a></td>
                            <td class="text-center">10/21/2017</td>
                            <td class="text-center">NO UPLINE</td>
                            <td class="text-center" style="color: green;">PHP 115,500.00</td>
                            <td class="text-left">
                                <!-- ACTION BUTTON -->
                                <div class="btn-group">
                                  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu">
                                    <li><a href="#">View Genealogy</a></li>
                                    <li><a href="#">Set as Inactive</li>
                                  </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3548</td>
                            <td class="text-center">Silver (CD)</td>
                            <td class="text-center"><a href="javascript:">Luke Glenn Jordan</a></td>
                            <td class="text-center">10/23/2017</td>
                            <td class="text-center"><a href="javascript:">Guillermo Tabligan (2314)</a></td>
                            <td class="text-center" style="color: green;">PHP 45,500.00</td>
                            <td class="text-left">
                                <!-- ACTION BUTTON -->
                                <div class="btn-group">
                                  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu">
                                    <li><a href="#">View Genealogy</a></li>
                                    <li><a href="#">Set as Inactive</li>
                                  </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    
</div>

@endsection
