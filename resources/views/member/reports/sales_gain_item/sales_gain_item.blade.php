@extends('member.layout')

@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title"> Sales Gain By Item</span>
            </h1>
        </div>
    </div>
</div>

@include('member.reports.filter.filter1')
@include('member.reports.output.sales_gain_item')

@endsection
@section('script')
<script type="text/javascript">

    var sales_gain_item = new sales_gain_item();

    function sales_gain_item()
    {
        init();

        function init()
        {
            event_run_report_click();
            // action_collaptible(true);
        }
    }

    function event_run_report_click()
    {
        $(document).on("click", ".run-report", function()
        {
            var serialize_data = $("form.filter").serialize()
            
            $(".load-data").load("/member/report/sales_gain_item?"+serialize_data+"&load_view=true .load-content", function()
            {
                // action_collaptible(true);
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
