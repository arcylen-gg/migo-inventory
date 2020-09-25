@extends('member.layout')
@section('content')
<div class="col-md-12 clearfix">
    {!! $a !!}
</div>
<div class="col-md-6">
    <form class="global-submit" method="post" action="/member/mlm/encashment/request/all/selected">
        {!! csrf_field() !!}
        <small><span class="color: gray">Remarks</span></small>
        <textarea class="form-control" name="remarks"></textarea>
        <hr>
        <button class="btn btn-success col-md-2">Process</button> <span class="alert-warning col-md-12">By using the Process Button, all checked request will be process. Note: Please verify all encashment details before processing.</span>
    </form>
</div>
<div class="col-md-6">
     <form class="global-submit" method="post" action="/member/mlm/encashment/deny/all/selected">
        {!! csrf_field() !!}
        <small><span class="color: gray">Remarks</span></small>
        <textarea class="form-control" name="remarks"></textarea>
        <hr>
        <button class="btn btn-danger col-md-2">Deny</button> <span class="alert-warning col-md-12">By using the Deny Button, all checked request will be denied and all the wallet will be returned to the account. Note: Please verify all encashment details before Denying.</span>
    </form>
</div>

@endsection
@section('script')
<script>
    function submit_done (data) {
        // body...

        if(data.status == 'success_new')
        {
            toastr.success(data.message);
            window.location = '/member/mlm/encashment';
        }
    }
</script>
@endsection
