@extends('member.layout')

@section('content')
{!! $head !!}

@include('member.reports.filter.filter1')
@include('member.reports.output.open_purchase_order_detailed')

@endsection

@section('script')
<script type="text/javascript">

    var open_purchase_order_detailed = new open_purchase_order_detailed();

    function open_purchase_order_detailed()
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
            
            $(".load-data").load("/member/report/open/purchase/orders/detailed?"+serialize_data+"&load_view=true .load-content", function()
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
