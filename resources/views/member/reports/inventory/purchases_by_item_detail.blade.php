@extends('member.layout')

@section('content')
{!! $head !!}

@include('member.reports.filter.filter1')
@include('member.reports.output.purchases_by_item_detail')

@endsection

@section('script')
<script type="text/javascript">

    var purchases_by_item_detail = new purchases_by_item_detail();

    function purchases_by_item_detail()
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
   
            $(".load-data").load("/member/report/open/purchase/item_detail?"+serialize_data+"&load_view=true .load-content", function()
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

    $(function()
    {
        $(".wrapper-top-scroll").scroll(function()
        {
            $(".wrapper-bottom-scroll").scrollLeft($(".wrapper-top-scroll").scrollLeft());
        });
        $(".wrapper-bottom-scroll").scroll(function()
        {
            $(".wrapper-top-scroll").scrollLeft($(".wrapper-bottom-scroll").scrollLeft());
        });
    });

</script>
@endsection
