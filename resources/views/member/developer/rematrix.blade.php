@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Rematrix</span>
                <small>
                    Rearrange the tree
                </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <label for="number_of_sponsor">Number of slots</label>
                <input class="form-control" id="number_of_sponsor" value="{{$slot_count}}" disabled>
            </div>
            <div class="col-md-6">
                <label for="rematrix_status">Rematrix Status</label>
                <input class="form-control" id="rematrix_status" value="----" disabled>
            </div>
<!--             <div class="col-md-12" style="position:relative">
                <div class="left-arrow"><h1 style="cursor:pointer;">Left</h1></div>
                <div class="number_container">
                    <span class="number" style="position:absolute; left:100%;">1</span>
                    <span class="number" style="position:absolute; left:25%;">2</span>
                    <span class="number" style="position:absolute; left:10%;">3</span>
                </div>
                <div class="right-arrow"><h1 style="cursor:pointer;">Right</h1></div>
            </div> -->
        </div>
    </div>
</div>
@endsection
<style type="text/css">

</style>
@section('script')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <script type="text/javascript">
var numItems = $('.item').length;
</script> -->
@endsection