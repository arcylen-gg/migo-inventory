@extends('member.layout')

@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title"> Sales with Bank Interest</span>
            </h1>
        </div>
    </div>
</div>

@include('member.reports.filter.filter1')
@include('member.reports.output.sales_bank_interest')

@endsection
@section('script')
<script type="text/javascript">

    var sales_bank_interest = new sales_bank_interest();

    function sales_bank_interest()
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
            
            $(".load-data").load("/member/report/sales_bank_interest?"+serialize_data+"&load_view=true .load-content", function()
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
