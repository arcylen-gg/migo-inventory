@extends('member.layout')

@section('content')
{!! $head !!}

@include('member.reports.filter.filter1')
@include('member.reports.output.purchases_by_vendor_summary')

@endsection

@section('script')
<script type="text/javascript">

    var open_purchase_vendor_summary = new open_purchase_vendor_summary();

    function open_purchase_vendor_summary()
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
   
            $(".load-data").load("/member/report/open/purchase/vendor_summary?"+serialize_data+"&load_view=true .load-content", function()
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
