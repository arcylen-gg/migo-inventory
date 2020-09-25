@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Transaction List</span>
            <small>
                This are the list of TRANSACTIONS that happened in the system.
            </small>
            </h1>
            <div class="dropdown pull-right">
                <button onclick="location.href=''" class="btn btn-def-white btn-custom-white"><i class="fa fa-check"></i> Secondary Command</button>
                <button onclick="location.href=''" class="btn btn-primary"><i class="fa fa-star"></i> Primary Command</button>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <ul class="nav nav-tabs">
        <li class="active change-tab pending-tab cursor-pointer" mode="pending"><a class="cursor-pointer"><i class="fa fa-check"></i> Active</a></li>
        <li class="cursor-pointer change-tab approve-tab" mode="approved"><a class="cursor-pointer"><i class="fa fa-trash"></i> Archived</a></li>
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
                <input type="text" class="form-control search-employee-name" placeholder="Search by employee name / number" aria-describedby="basic-addon1">
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
                                    <th class="text-center">Transaction ID</th>
                                    <th class="text-center">Remaning Balance</th>
                                    <th class="text-center">DATE CREATED</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">TRANSACTION COUNT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_transaction as $transaction)
                                <tr>
                                    <td class="text-center">{{ $transaction->transaction_id_shop }}</td>
                                    <td class="text-center">{!! $transaction->display_balance !!}</td>
                                    <td class="text-center">{{ $transaction->transaction_origin_date }}</td>
                                    <td class="text-center">{{ $transaction->transaction_origin_status }}</td>
                                    <td class="text-center"><a class="popup" size="lg" link="/member/cashier/transactions/view_list/{{$transaction->transaction_id}}">{{ $transaction->transaction_count }}</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $_transaction->render() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection