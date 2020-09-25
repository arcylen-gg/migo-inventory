@extends('member.payroll2.employee_dashboard.layout')
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="/employee">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">{{ $page }}</li>
  </ol>
<div id="main-wrapper">
    <div class="row">
        <div class="col-md-3">
            <img src="{{ $company->payroll_company_logo }}" class="rounded float-right" alt="{{ $company->payroll_company_name }}" height="200" width="200">
            <h3 class="text-center">{{ $company->payroll_company_name }}</h3>
            <hr>
            <ul class="list-unstyled text-center">
                <li><p><i class="fa fa-map-marker" style="font-size:18px;color:#ff1a1a"></i> {{ $company->payroll_company_address }}</p></li>
                <li><p><i class="fa fa-phone-square" style="font-size:18px"></i> {{ $company->payroll_company_contact}} </p></li>
                <li><p><i class="fa fa-envelope m-r-xs" style="color:#4db8ff"></i><a href="#"> {{ $company->payroll_company_email }} </a></p></li>
            </ul>
        </div>
        <div class="col-md-9 m-t-lg">
            @if($company->payroll_parent_company_id != 0)
                <div class="row">
                    <div class="col-md-12">
                        <small>Is Sub-Company of:</small>
                        <input type="text" placeholder="Parent Company" class="form-control" value="{{$company->payroll_company_name}}" disabled>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <small>Company Name</small>
                    <input type="text" placeholder="Company Name" class="form-control" value="{{$company->payroll_company_name}}" disabled>
                </div>
            </div>  
            <div class="row">
                <div class="col-md-6">
                    <small>Company Code</small>
                    <input type="text" placeholder="Company Code" class="form-control" value="{{$company->payroll_company_code}}" disabled>
                </div>
                <div class="col-md-6">
                    <small>Company RDO</small>
                    <input type="text" placeholder="Company RDO" class="form-control" value="{{$company->payroll_rdo_id.' - '.$company->rdo_location}}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <small>Company Address</small>
                    <input type="text" placeholder="Company Address" class="form-control" value="{{$company->payroll_company_address}}" disabled>
                </div>
            </div>  
            <div class="row">
                <div class="col-md-6">
                    <small>Company Contact</small>
                    <input type="text" placeholder="Company Contact" class="form-control" value="{{$company->payroll_company_contact}}" disabled>
                </div>
                <div class="col-md-6">
                    <small>Company Email</small>
                    <input type="text" placeholder="Company Email" class="form-control" value="{{$company->payroll_company_email}}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <small>Nature of Business</small>
                    <input type="text" placeholder="Nature of Business" class="form-control" value="{{$company->payroll_company_nature_of_business}}" disabled>
                </div>
                <div class="col-md-6">
                    <small>Company Date Started</small>
                    <input type="text" placeholder="Company Date Started" class="form-control" value="{{$company->payroll_company_date_started}}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <small>Bank</small>
                    <input type="text" placeholder="Bank" class="form-control" value="{{$company->bank_name}}" disabled>
                </div>
                <div class="col-md-6">
                    <small>Bank Account Number</small>
                    <input type="text" placeholder="Bank Account Number" class="form-control" value="{{$company->payroll_company_account_no}}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <small>Company TIN</small>
                    <input type="text" placeholder="Company TIN" class="form-control" value="{{$company->payroll_company_tin}}" disabled>
                </div>
                <div class="col-md-6">
                    <small>Company SSS</small>
                    <input type="text" placeholder="Company SSS" class="form-control" value="{{$company->payroll_company_sss}}" disabled>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <small>Company Philhealth</small>
                    <input type="text" placeholder="Company Philhealth" class="form-control" value="{{$company->payroll_company_philhealth}}" disabled>
                </div>
                <div class="col-md-6">
                    <small>Company PAGIBIG</small>
                    <input type="text" placeholder="Company PAGIBIG" class="form-control" value="{{$company->payroll_company_pagibig}}" disabled>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>

@endsection
