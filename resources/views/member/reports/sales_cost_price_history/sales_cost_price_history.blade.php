@extends('member.layout')
@section('content')
{!! $head !!}
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title"> Sales & Cost Price History</span>
            </h1>
        </div>
    </div>
</div>

@include('member.reports.filter.filter1')
@include('member.reports.output.sales_cost_price_history')

@endsection
@section('script')
<script type="text/javascript">

    var sales_cost_price_history = new sales_cost_price_history();

    function sales_cost_price_history()
    {
        init();

        function init()
        {
            event_run_report_click();
            action_collaptible(true);
        }
    }

    function event_run_report_click()
    {
        $(document).on("click", ".run-report", function()
        {
            var serialize_data = $("form.filter").serialize()
            
            $(".load-data").load("/member/report/sales_cost_price_history?"+serialize_data+"&load_view=true .load-content", function()
            {
                action_collaptible(true);
            });
        });
    }

    function submit_done(data)
    {
        if(data.status == 'success_plain')
        {
            toastr.success('Success');
        }
    }

</script>
@endsection
