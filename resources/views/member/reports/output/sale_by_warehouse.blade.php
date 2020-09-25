<div class="report-container">
    <div class="panel panel-default panel-block panel-title-block panel-report">
        <div class="panel-heading">
            @include('member.reports.report_header');
            <div class="table-repsonsive">
                <table class="table table-condensed collaptable">
                    <thead>
                        
                        <tr>
                            @foreach($report_field as $key => $value)
                                    <th>{{$key == 'z_um' ? 'Qty' : $value->report_field_label}}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($_sales) > 0)
                            @foreach($_sales as $w_key => $w_value)
                            <tr data-id="{{$w_key}}" data-parent=""  style="background-color: #dedede;" >
                                <td colspan="20">{{$w_value['warehouse_name']}}</td>
                            </tr>
                            <?php $balance = 0; $t_sales = 0; ?>
                            @if(isset($w_value['sales']))
                                    @foreach($w_value['sales'] as $f_key => $f_value)
                                        <?php
                                                if($f_value->account_code == 'discount-sale')
                                                {
                                                    $t_sales -= $f_value->jline_amount;                      
                                                    $balance -= $f_value->jline_amount;
                                                }
                                                else
                                                {
                                                    $t_sales += $f_value->jline_amount;
                                                    $balance += $f_value->jline_amount;                     
                                                }
                                                $f_value->balance = currency('PHP', $balance);
                                                $f_value->jline_amount = currency('PHP', $f_value->jline_amount);
                                        ?>
                                        <tr data-id="a_{{$f_key}}" data-parent="{{$w_key}}">
                                            @foreach($report_field as $r_f_key => $r_f_value)
                                                @if($f_value->account_code == 'discount-sale' && $r_f_key == 'jline_amount')
                                                <td>-{{$f_value->$r_f_key}}</td>
                                                @elseif($r_f_key  == 'item_price')
                                                <td>{{currency('PHP', $f_value->item_price)}}</td>
                                                @elseif($r_f_key  == 'item_rate')
                                                <td>{{currency('PHP', $f_value->item_rate)}}</td>
                                                @else
                                                <td>{{$r_f_key == 'z_um' ? $f_value->warehouse_quantity . $f_value->$r_f_key : $f_value->$r_f_key}}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    <tr data-id="a_{{$f_key}}" data-parent="{{$w_key}}">
                                        <th colspan="11" class="text-left">TOTAL SALES OF {{strtoupper($w_value['warehouse_name'])}}</th>
                                        <th>{{currency('PHP',$t_sales)}}</th>
                                        <th>{{currency('PHP',$balance)}}</th>

                                    </tr>
                            @else
                                <tr>
                                    <td colspan="20"><center>No Record on this warehouse</center></td>
                                </tr>
                            @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="20"><center>No Record on this warehouse</center></td>
                            </tr> 
                        @endif                       
                    </tbody>
                </table>
            </div>
            <h5 class="text-center">---- {{$now or ''}} ----</h5>
        </div>
    </div>
</div>