<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading row clearfix">
    	<form class="global-submit filter" method="post" action="{{$action}}" >
    	{!! csrf_field() !!}
    	<input type="hidden" name="report_type" value="plain" class="report_type_i">
    	<input type="hidden" name="report_field_type" class="report_field_type" value="{{$report_code or ''}}">
        <div class="form-group">
            <div class="col-md-2">
                <select class="form-control input-sm report_period" name="report_period">
                    <option value="all">All Dates</option>
                    <option value="custom">Custom</option>
                    <option value="today">Today</option>
                    <option value="this_week">This Week</option>
                    <option value="this_week_to_date">This Week to Date</option>
                    <option value="this_month">This Month</option>
                    <option value="this_month_to_date">This Month to Date</option>
                    <option value="this_quarter">This Quarter</option>
                    <option value="this_quarter_to_date">This Quarter to Date</option>
                    <option value="this_year">This Year</option>
                </select>
            </div>
            <div class="col-md-2"><input type="text" class="form-control from_report_a datepicker" name="from" placeholder="Start Date"></div>
            <div class="col-md-2"><input type="text" class="form-control form_report_b datepicker" name="to" placeholder="End Date"></div>
            <div class="col-md-3"><button class="btn btn-primary run-report" onclick="$('.report_type_i').val('plain')" >Run Report</button></div>
            <button class="btn btn-custom-red-white margin-right-10 btn-pdf pull-right" onclick="report_file('pdf')"><i class="fa fa-file-pdf-o"></i>&nbsp;Export to PDF</button>
            <button class="btn btn-custom-green-white margin-right-10 btn-pdf pull-right" onclick="report_file('excel')"><i class="fa fa-file-excel-o"></i>&nbsp;Export to Excel</button>
        </div>
        <div  style="margin-top: 15px" class="form-group">
            <div class="col-md-2 " style="padding: 5px">
                <select class="form-control input-sm" name="warehouse_id">
                    @foreach($_warehouse as $warehouse)
                    <option value="{{$warehouse->warehouse_id}}">{{$warehouse->warehouse_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4" style="padding: 5px">
                <select class="form-control input-sm" name="transaction_type">
                    <option value="">All Type of Transaction</option>
                    <option value="online">Online Transaction</option>
                    <option value="offline">Offline Transaction</option>
                </select>
            </div>

            <div class="col-md-3" style="padding: 5px">
                <select class="form-control input-sm" name="code_type">
                    <option value="membership_code">Membership Code</option>
                    <option value="product_code">Product Code</option>
                </select>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">

    var new_report = new new_report();
    function new_report()
    {
        init();

        function init()
        {
            event_run_report_click();
        }

        function event_run_report_click()
        {
            $(document).on("click", ".run-report", function()
            {
                $.ajax({
                url: '/member/report/accounting/date_period',
                dataType: 'json',
                data: $("form.filter").serialize(),
                })
                .done(function(data) {
                    if(data.period != 'all')
                    {
                        $(".from_report_a").val(data.start_date);
                        $(".form_report_b").val(data.end_date);
                    }
                    else
                    {
                        $(".from_report_a").val("");
                        $(".form_report_b").val("");
                    }
                })
                .fail(function() {
                    console.log("error");
                })
            });
        }
    }

    function report_file(type)
    {
        var link        = $("form.filter").attr("action");
        var serialize   = $("form.filter").serialize();
        var link        = link + '?' + serialize + '&report_type=' + type;
        console.log(link);
        window.open(link);

        // var date_from  = $('.from_report_a').val();
        // var date_to    = $('.form_report_b').val();
        // var period     = $('.report_period').val();
        // var link       = $("form.filter").attr("action");
        // var report_field_type = $('.report_field_type').val();
        // var token      = $("input[name='_token']").val();
        // var link       = link + '?_token='+token+'&report_type=' + type + '&report_field_type=' + report_field_type + '&report_period=' + period + '&from=' + date_from + '&to=' + date_to

        // console.log(link);
        // // window.open( link + '?report_type=' + type + '&from=' + date_from + '&to=' + date_to + '&report_field_type=' + report_field_type + '&report_period=' + period);
        // window.open(link);
    }
</script>