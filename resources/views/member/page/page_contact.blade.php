@extends('member.layout')
@section('content')
<form method="post">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Page <i class="fa fa-angle-double-right"></i> Contact Information</span>
                    <small>
                        Edit contact information.
                    </small>
                </h1>
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right">Update</button>
            </div>
        </div>
    </div>

    <div class="panel panel-default panel-block panel-title-block panel-gray load-get">
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                <div style="margin: 25px 0;">
                    <label>Email Address</label>
                    <input type="email" class="form-control" value="{{ isset($setting['contact_email_address']) ? $setting['contact_email_address'] : Request::old('contact_email_address') }}" name="contact_email_address">
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@section('css')

@endsection
@section('script')

@endsection