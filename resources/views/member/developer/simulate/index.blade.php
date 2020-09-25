@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Simulate</span>
                <small>
                    Simulation of Slots
                </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
        <form class="global-submit" method="post" action="/member/developer/simulate/submit">
        {!! csrf_field() !!}
            <div class="col-md-12"><center>Memberships</center><hr></div>
            @foreach($membership as $key => $value)
            <div class="col-md-3">
                <label class="checkbox-inline"><input type="checkbox" name="membership_id[{{$value->membership_package_id}}]" value="{{$value->membership_package_id}}">{{$value->membership_package_name}}</label>
            </div>
            @endforeach
            <div  class="col-md-12"><hr /></div>
            <div class="col-md-6">
                <label>No. of Customer</label>
                <input type="number"  class="form-control" name="no_of_customer" required="required">
            </div>
            <div class="col-md-6">
                <label>No. of Slots / Customer</label>
                <input type="number"  class="form-control" name="no_of_slots_customer" required="required">
            </div>
            <div class="col-md-6">
                <label>No. of Slots</label>
                <input type="number"  class="form-control" name="no_of_slots" required="required">
            </div>
            <div class="col-md-6">
                <label>No. of Downline /slots</label>
                <input type="number"  class="form-control" name="no_of_downlines" required="required">
            </div>
            <div  class="col-md-12"><hr /></div>
            <div class="col-md-6">
                <label>Password</label>
                <input type="password"  class="form-control" name="password" required="required">
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary pull-right">Simulate</button>
            </div>
        </form>    
        </div>
    </div>
</div>
@endsection
<style type="text/css">

</style>
@section('script')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    function submit_done (data) 
    {
        if(data.status =='warning')
        {
            var errors = data.error;
            errors.forEach(function(element) {
                toastr.warning(element);
            });
        }
    }
</script>
@endsection