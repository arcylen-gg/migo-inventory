@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-home"></i>
            <h1>
            <span class="page-title">Commission List</span>
            <small>
                List of Sold Property
            </small>
            </h1>
            <div class="dropdown pull-right">            
                <a class="btn btn-def-white btn-custom-white" href="/member/cashier/commission_calculator/import"><i class="fa fa-check"></i> Import Transaction</a>
                <button class="btn btn-primary popup" size="lg" link="/member/cashier/commission_calculator/create"><i class="fa fa-star"></i> Calculate Commission</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <ul class="nav nav-tabs">
        <li class="active change-tab pending-tab cursor-pointer" mode="pending"><a class="cursor-pointer"><i class="fa fa-check"></i> Active</a></li>
        <li class="cursor-pointer change-tab approve-tab" mode="approved"><a class="cursor-pointer"><i class="fa fa-trash"></i> Fulfilled</a></li>
    </ul>
    <div class="search-filter-box">
        <div class="col-md-3" style="padding: 10px">
            <select class="form-control">
                <option value="0">Filter Sample 001</option>
            </select>
        </div>
        <div class="col-md-3" style="padding: 10px">
            <select class="form-control">
                <option value="0">Filter Sample 002</option>
            </select>
        </div>
        <div class="col-md-2" style="padding: 10px">
        </div>
        <div class="col-md-4" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control search-employee-name" placeholder="Search ..." aria-describedby="basic-addon1">
            </div>
        </div>
    </div>
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th >Property Details</th>
                                    <th class="text-center" width="300px">Sales Agent</th>
                                    <th class="text-center" width="300px">Total Commision</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($_list) > 0)
                                @foreach($_list as $key => $list)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>
                                        <div><b>{{$list->item_name}}</b> </div>
                                        <small>TSP : {{currency('P ',$list->total_selling_price)}}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ucwords($list->salesrep_fname.' '.$list->salesrep_mname.' '.$list->salesrep_lname)}}
                                    </td>
                                    <td class="text-center">
                                        {{currency('P ',$list->total_commission)}}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                          <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-menu-custom">
                                            <li><a href="">Print</a></li>
                                          </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr><td colspan="5" class="text-center">NO PROCESS YET</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection