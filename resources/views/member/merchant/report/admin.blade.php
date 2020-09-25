@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title">Merchant Report</span>
            </h1>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-default panel-block panel-title-block clearfix col-md-12" id="top">
        <div class="panel-body" style="overflow-x:auto;">
            <label><small style="color: gray">Select Report</small></label>
            <select class="form-control report_select">
                @foreach($report as $key => $value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>

            <label><small style="color: gray">From</small></label>
            <input type="date" class="form-control from">

            <label><small style="color: gray">To</small></label>
            <input type="date" class="form-control to">

            <hr>


            <table class="table table-hover table-bordered">
                @foreach($merchant as $key => $value)
                    <tr>
                        <td style="text-transform: uppercase;">
                            {{$value->user_first_name}} {{$value->user_last_name}}<br>
                            <label><small style="color: green">Overall Sales: {{currency('PHP', $value->overall)}}</small></label>
                            <button class="btn btn-success pull-right" onClick="show_report('{{$value->user_id}}')"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>       
<div class="col-md-8">
    <div class="panel panel-default panel-block panel-title-block clearfix col-md-12" id="top">
        <div class="panel-body" style="overflow-x:auto;">
            <div class="report_body"></div>
        </div>
    </div>
</div>         
@endsection

@section('script')
<script type="text/javascript">
    function show_report(user_id) 
    {
        var report_select = $('.report_select').val();
        var from = $('.from').val();
        var to = $('.to').val();
        if(!from)
        {
            return alert('From is required');
        }
        if(!to)
        {
            return alert('To is required');
        }
        if(from > to)
        {
            return alert('From must not be greater than to');
        }
        show_loader('report_body');
        var link = '/member/merchant/report/view?user_id=' + user_id  +'&from=' + from + '&to=' + to + '&report_select=' + report_select;
        $('.report_body').load(link);
    }
    function show_loader(div)
    {
        $('.' + div).html('<center><i class="fa fa-spinner fa-spin" style="font-size:32px"></i></center>');
    }
</script>
@endsection