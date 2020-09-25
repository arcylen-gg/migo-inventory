@extends('member.layout')

@section('content')
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-tags"></i>
            <h1>
                <span class="page-title"> Inventory Valuation Summary</span>
            </h1>
        </div>
    </div>
</div>

@include('member.reports.filter.filter6')
@include('member.reports.output.inventory_summary')

@endsection
@section('script')
<script type="text/javascript">

    var inventory_summary = new inventory_summary();

    function inventory_summary()
    {
        init();

        function init()
        {
            event_run_report_click();
            //action_collaptible(true);
            initialize_select();
        }
    }
  function initialize_select()
    {
        $(".filter-item-type").globalDropList({
            hasPopup : 'false',
            width: "100%",
            placeholder: "All Category"
        });
        $(".category-select").globalDropList({
            hasPopup : 'false',
            width: "100%",
            placeholder: "All Category"
        });
    }

    function event_run_report_click()
    {
        $(document).on("click", ".run-report", function()
        {
            var serialize_data = $("form.filter").serialize();
            
            $(".load-data").load("/member/report/inventory/summary?"+serialize_data+"&load_view=true .load-content", function()
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
