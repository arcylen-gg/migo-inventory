@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">API Page for Payroll Application</span>
            <small style="font-size: 14px; color: gray;">
                You can download a copy of payroll application on this page.
            </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
 <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-4">            
                <label>APP KEY</label>
                <input type="text" class="form-control" readonly value="{{ $app_key }}">
            </div>
            <div class="col-md-8">            
                <label>APP SECRET</label>
                <input type="text" class="form-control" readonly value="{{ $app_secret  }}">
            </div>
        </div>
    </div>
</div>


@endsection