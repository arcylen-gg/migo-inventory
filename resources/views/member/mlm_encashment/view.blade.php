@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-12" >
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
                <center>Process Info</center>
                <hr>
                <div class"table-responsive">
                    <table class="table table-condensed table-bordered">
                        <thead>
                            <th>Process ID</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Tax</th>
                            <th>Processing Fee</th>
                            <th>Sum</th>
                        </thead>
                        <tbody>
                        @if(count($encashment_process) != 0)
                            <tr>
                                <td>{{$encashment_process->encashment_process}}</td>
                                <td>{{$encashment_process->enchasment_process_from}}</td>
                                <td>{{$encashment_process->enchasment_process_to}}</td>
                                <td>
                                    @if($encashment_process->enchasment_process_tax_type == 0)
                                        {{currency('PHP', $encashment_process->enchasment_process_tax)}}
                                    @else 
                                        {{$encashment_process->enchasment_process_tax}}%
                                    @endif
                                </td>
                                <td>
                                    {{$encashment_process->enchasment_process_p_fee}}
                                    @if($encashment_process->enchasment_process_p_fee_type == 1)
                                    {{$encashment_process->enchasment_process_p_fee}}%
                                    @else
                                    {{currency('PHP', $encashment_process->enchasment_process_p_fee)}}
                                    @endif
                                </td>
                                <td>{{currency('PHP', $encashment_process->encashment_process_sum)}}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="40">No Record Found.</td>
                            </tr>
                        @endif    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6" >
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
                <center>Process Info</center>
                <hr>
                <div class"table-responsive">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <th>Slot No</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Processing Fee</th>
                        <th>Tax</th>
                        <th>Total</th>
                        <th>Breakdown</th>
                    </thead>
                    <tbody>
                    @if(count($slots) >=1)
                        @foreach($slots as $key => $value)
                            <?php 
                                $currency = $value->encashment_process_currency;
                                $convertion = $value->encashment_process_currency_convertion;
                                $tax_converted = $value->enchasment_process_tax * $convertion;
                                $process_fee = $value->enchasment_process_p_fee * $convertion;
                                $taxed = $value->encashment_process_taxed * $convertion;
                                $total = $value->wallet_log_amount * $convertion;
                                $denied = $value->wallet_log_denied_amount * $convertion;
                            ?>
                        <tr>
                            <td>{{$value->slot_no}}</td>
                            <td>{{name_format_from_customer_info($value)}}</td>
                            <td>{{$value->wallet_log_amount * (-1)}}</td>
                            <td>
                                <?php 
                                $value2 = $value->wallet_log_amount * (-1);
                                $p_fee_type = $encashment_process->enchasment_process_p_fee_type;
                                $p_fee = $encashment_process->enchasment_process_p_fee;
                                $tax_p =    $encashment_process->enchasment_process_tax_type;
                                $tax = $encashment_process->enchasment_process_tax;
                                if($p_fee_type == 0)
                                {
                                    $value2 = $value2 - $p_fee;
                                }
                                else
                                {
                                    $p_fee = ($value2 * $p_fee)/100;
                                    $value2 = $value2 - $p_fee;
                                }

                                if($tax_p == 0)
                                {
                                    $value2 = $value2 - $tax;
                                }
                                else
                                {
                                    $tax = ($value2 * $tax)/100;
                                    $value2 = $value2-$tax;
                                }
                                ?>
                                {{$p_fee}}
                            </td>
                            <td>{{$tax}}</td>
                            <td>{{$value->encashment_process_taxed}}</td>
                            <td>
                                <a class="btn btn-primary" href="javascript:" onClick="show_breakdown({{$encashment_process->encashment_process}}, {{$value->slot_id}})"> 
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="40"><center>No Record Found.</center></td>
                        </tr>
                    @endif    
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>     
<div class="panel panel-default panel-block panel-title-block panel-gray col-md-6" >
    <div class="tab-content">
        <div id="all-orders" class="tab-pane fade in active">
            <div class="panel-heading">
                <center>Breakdown</center>
                <hr>
                <div class="breakdown_slot_c"></div>
            </div>
        </div>
    </div>
</div>                           
@endsection
@section('script')
<script type="text/javascript">
function submit_done(data)
{
    if(data.status == 'success')
    {
        toastr.success(data.message);
    }
    if(data.status ='warning')
    {
        toastr.warning(data.message);
    }
    if(data.status = 'success_process')
    {
        show_success(data);
    }
}
function show_success(data)
{
    show_breakdown(data.encashment_process, data.slot_id);
    toastr.success(data.message);
}
function show_breakdown(encashment_process, slot_id)
{
    $('.breakdown_slot_c').html('<center><div class="loader-16-gray"></div></center>');
    $('.breakdown_slot_c').load('/member/mlm/encashment/view/breakdown/'+encashment_process+'/' +slot_id);
}
function show_pdf(encashment_process, slot_id)
{
    
}
</script>
@endsection
