@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Membership Codes Receipt</span>
                <small>
                    The membership code receipt are shown here.
                </small>
            </h1>
            <a href="/member/mlm/code/sell" class="panel-buttons btn btn-primary pull-right">Sell Codes</a>
            <a href="/member/mlm/code" class="panel-buttons btn btn-default pull-right">View Code(s)</a>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body">
        <div class="form-horizontal">
            <div class="form-group">
                <div class="col-md-12 table-responsive">
                    <table class="table table-condensed">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
                    

@endsection
@section('script')

</script>
@endsection