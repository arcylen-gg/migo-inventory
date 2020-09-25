@extends('member.layout')

@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title"> Best Seller Report By Item Name</span>
            </h1>
        </div>
    </div>
</div>

@include('member.reports.filter.filterbestseller')
@include('member.reports.best_seller_by_pattern.best_seller_by_pattern_output')

@endsection
@section('script')
<script type="text/javascript">

    var best_seller = new best_seller();

    function best_seller()
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
            
            $(".load-data").load("/member/report/accounting/sale/best_seller_item_by_pattern?"+serialize_data+"&load_view=true .load-content", function()
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
