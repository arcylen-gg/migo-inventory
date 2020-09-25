@extends('member.layout')

@section('content')
{!! $head !!}


@include('member.reports.filter.filter1')
@include('member.reports.accounts_payable.ap_output')
@endsection

@section('script')
<script type="text/javascript">

    var ar_report = new ar_report();

    function ar_report()
    {
        init();

        function init()
        {
            event_run_report_click();
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
            
            $(".load-data").load("/member/report/accounts_payable?"+serialize_data+"&load_view=true .load-content");
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