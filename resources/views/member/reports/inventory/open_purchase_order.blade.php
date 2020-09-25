@extends('member.layout')

@section('content')
{!! $head !!}

@include('member.reports.filter.filter6')
@include('member.reports.output.open_purchase_order')

@endsection

@section('script')
<script type="text/javascript">

    var open_purchase_order = new open_purchase_order();

    function open_purchase_order()
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
            var serialize_data = $("form.filter").serialize();
            
            $(".load-data").load("/member/report/open/purchase/order?"+serialize_data+"&load_view=true .load-content", function()
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
