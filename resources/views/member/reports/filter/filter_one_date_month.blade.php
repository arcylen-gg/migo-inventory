<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <form class="global-submit filter" method="post" action="{{$action}}" >
            {!! csrf_field() !!}
            <input type="hidden" name="report_type" value="plain" class="report_type_i">
            <!-- <input type="hidden" name="report_field_type" class="report_field_type" value="{{$report_code or ''}}"> -->
            <!-- <input type="hidden" name="vendor_id" class="vendor_id" value="{{$vendor_id or ''}}"> -->
            <div>
                <div class="col-md-2">
                    <select name="selected_month" class="select-month form-control input-sm">
                        @foreach($_month as $key => $month)
                        <option  value="{{strlen($key+1) == 1 ? '0'.($key+1) : ($key+1)}}">{{$month}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="selected_year" class="select-year form-control input-sm">
                        @foreach($_year as $yr)
                        <option value="{{$yr}}">{{$yr}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><button class="btn btn-primary run-report" onclick="$('.report_type_i').val('plain')" >Run Report</button></div>
                <button class="btn btn-custom-red-white margin-right-10 btn-pdf pull-right" onclick="report_file('pdf')"><i class="fa fa-file-pdf-o"></i>&nbsp;Export to PDF</button>
                <button class="btn btn-custom-green-white margin-right-10 btn-pdf pull-right" onclick="report_file('excel')"><i class="fa fa-file-excel-o"></i>&nbsp;Export to Excel</button>
            </div>
        </form>
    </div>
</div>