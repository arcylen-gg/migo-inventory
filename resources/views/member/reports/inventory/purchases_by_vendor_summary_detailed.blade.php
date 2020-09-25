@extends('member.layout')

@section('content')
{!! $head !!}

@include('member.reports.filter.filter1')
@include('member.reports.output.purchases_by_vendor_summary_detailed')

@endsection

@section('script')
<script type="text/javascript">

    var purchases_by_vendor_summary_detailed = new purchases_by_vendor_summary_detailed();

    function purchases_by_vendor_summary_detailed()
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
            var vendor_id = $(".vendor_id").val();
            $(".load-data").load("/member/report/vendor/detailed/"+vendor_id+"?"+serialize_data+"&load_view=true .load-content", function()
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
