@extends('mlm.layout')
@section('content')
<div class="container text-center">
    <div class="row vcenter" style="margin-top: 20%;">
      <div class="col-md-12">
        <div class="error-template">
          <h1 class="oops">Oops!</h1>
          <h2 class="message">403 Permission Denied</h2>
          <div class="error-details">
            Sorry, you do not have access to this page, please contact your administrator. You must have a slot to view this page.
          </div>
        </div>
      </div>
    </div>
@endsection
