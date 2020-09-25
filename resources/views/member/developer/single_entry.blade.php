@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Auto Entry</span>
                <small>
                    Auto Entry of slot
                </small>
            </h1>
        </div>
    </div> 
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
        <form method="POST" action="/member/developer/single_entry/submit">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="col-md-6">
                <label for="rematrix_status">Slot Id</label>
                <input type="text" class="form-control" name="slot_id">
            </div>
            <div class="col-md-12" style="min-height: 10px;"></div>
            <div class="col-md-2 pull-right">
                <button type="submit" class="form-control btn-custom-primary">Single Entry</button>
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

</script>
@endsection