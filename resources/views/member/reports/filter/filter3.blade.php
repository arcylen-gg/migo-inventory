<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
    	<form class="global-submit filter" method="post" action="{{$action}}" >
    	{!! csrf_field() !!}
    	<input type="hidden" name="report_type" value="plain" class="report_type_i">
    	<input type="hidden" name="report_field_type" class="report_field_type" value="{{$report_code or ''}}">
        <div>
            <button class="btn btn-custom-red-white margin-right-10 btn-pdf pull-right" onclick="report_file('pdf')"><i class="fa fa-file-pdf-o"></i>&nbsp;Export to PDF</button>
            <button class="btn btn-custom-green-white margin-right-10 btn-pdf pull-right" onclick="report_file('excel')"><i class="fa fa-file-excel-o"></i>&nbsp;Export to Excel</button>
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

        }
    }
</script>