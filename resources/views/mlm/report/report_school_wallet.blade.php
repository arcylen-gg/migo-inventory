@extends('mlm.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block col-md-12" id="top">
    <div class="panel-heading" style="background-color: white !important">
        <div>
            <span class="pull-right text-success"><h2>Current School Wallet : {{number_format($current_school_wallet)}}</h2></span>
            <br>
            <br>
            <br>
        </div>
    </div>
</div>        
<div class="panel panel-default panel-block panel-title-block col-md-12" id="top">
    <div class="panel-heading" style="background-color: white !important">
        <div>
            <center>Report</center>
            <div class="table-responsive">
                <table class="table table-condensed table-sm table-bordered" style="background-color: white !important">
                <?php 
                $header['merchant_school_date'] = 'Date';
                $header['merchant_school_s_id'] = 'Student ID';
                $header['merchant_school_s_name'] = 'Student Name';
                $header['merchant_school_anouncement'] = 'Anouncement';
                $header['merchant_school_amount'] = 'Amount';
                $header['merchant_school_amount_new'] = 'New Amount';
                ?>
                    <thead>
                        <tr>
                            @foreach($header as $key => $value)
                                <th>{{$value}}</th>
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($reciept) >= 1)
                            @foreach($reciept as $key => $value)
                                <?php 
                                $value->merchant_school_amount = number_format($value->merchant_school_amount);
                                $value->merchant_school_amount_new = number_format($value->merchant_school_amount_new);
                                ?>
                                <tr>
                                    @foreach($header as $key2 => $value2)
                                        <td>{{$value->$key2}}</td>
                                    @endforeach
                                    <td> <a href="javascript:" onClick="show_repciept('{{$value->merchant_school_id}}', 'pdf')">PDF</a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="20"><center> No Report Availble</center></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>             
@endsection

@section('js')
<script type="text/javascript">
function show_repciept(merchant_school_id, type)
{
    if(type == 'pdf')
    {
        var link = '/mlm/report/merchant_school/get?merchant_school_id=' + merchant_school_id + "&pdf=true";
        window.open(link);
    }
    else
    {
        console.log(1);
        var link = '/mlm/report/merchant_school/get?merchant_school_id=' + merchant_school_id;
        $('.reciept_append').html('<center><div style="margin: 100px auto;" class="loader-16-gray"></div></center>');
        $('.reciept_append').load(link);
    }
    
}
</script>
@endsection

