@extends('member.layout')

@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title"> Inventory Adjustment Detailed</span>
            </h1>
        </div>
    </div>
</div>

@include('member.reports.filter.filter9')
@include('member.reports.output.adjustment_detailed')

@endsection
@section('script')
<script type="text/javascript">

    var inventory_detailed = new inventory_detailed();

    function inventory_detailed()
    {
        init();

        function init()
        {
            event_run_report_click();
            //action_collaptible(true);
        }
    }

    function event_run_report_click()
    {
        $(document).on("click", ".run-report", function()
        {
            var serialize_data = $("form.filter").serialize();
            var item_id = $('.item_id').val();
            $(".load-data").load("/member/report/inventory/detailed/"+item_id+"?"+serialize_data+"&load_view=true .load-content", function()
                {
                    //action_collaptible(true);
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
