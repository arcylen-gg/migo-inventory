@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Payroll Biometric &raquo; Payroll Biometric Records</span>
                <small>
                Select Date
                </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="row">
            <div class="col-md-2">
                <small>Date From</small>
                <input type="text" name="date_from" class="form-control datepicker date_from">
            </div>
           
            <div class="col-md-2">
                <small>Date To</small>
                <input type="text" name="date_to" class="form-control datepicker date_to">
            </div>

            <div class="col-md-2">

                <button class="btn btn-primary btn-show" style="margin-top: 20px">SHOW</button>
            </div>

        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-table hidden">
    <div class="panel-body form-horizontal">
        <div class="form-group tab-content panel-body employee-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive biometric-table-load">
                    
                </div>
            </div>
        </div>
    </div>
</div>   
@endsection
@section('script')
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/payroll/payroll_biometric.js"></script>
@endsection