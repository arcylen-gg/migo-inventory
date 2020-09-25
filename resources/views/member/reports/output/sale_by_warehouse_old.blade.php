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
                        @foreach($warehouse_all as $w_key => $w_value)
                        <tr data-id="{{$w_key}}" data-parent=""  style="background-color: #dedede;" >
                            <td colspan="20">{{$w_value->warehouse_name}}</td>
                        </tr>
                        <?php $balance = 0; $t_sales = 0; ?>
                        @if(isset($filter[$w_key]))
                                @foreach($filter[$w_key] as $f_key => $f_value)
                                        @if(isset($sales[$f_key]))
                                                <?php
                                                        if($sales[$f_key]->account_code == 'discount-sale')
                                                        {
                                                            $t_sales -= $sales[$f_key]->jline_amount;                      
                                                            $balance -= $sales[$f_key]->jline_amount;
                                                        }
                                                        else
                                                        {
                                                            $t_sales += $sales[$f_key]->jline_amount;
                                                            $balance += $sales[$f_key]->jline_amount;                     
                                                        }
                                                        $sales[$f_key]->balance = currency('PHP', $balance);
                                                        $sales[$f_key]->jline_amount = currency('PHP', $sales[$f_key]->jline_amount);
                                                ?>
                                                <tr data-id="a_{{$f_key}}" data-parent="{{$w_key}}">
                                                    @foreach($report_field as $r_f_key => $r_f_value)
                                                        @if($sales[$f_key]->account_code == 'discount-sale' && $r_f_key == 'jline_amount')
                                                        <th>-{{$sales[$f_key]->$r_f_key}}</th>
                                                        @else
                                                        <th>{{$r_f_key == 'z_um' ? $sales[$f_key]->warehouse_quantity . $sales[$f_key]->$r_f_key : $sales[$f_key]->$r_f_key . $sales[$f_key]->$r_f_key }}</th>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                        @endif
                                @endforeach
                                <tr data-id="a_{{$f_key}}" data-parent="{{$w_key}}">
                                    <th colspan="8" class="text-left">TOTAL SALES OF {{strtoupper($w_value->warehouse_name)}}</th>
                                    <th>{{currency('PHP',$t_sales)}}</th>
                                    <th>{{currency('PHP',$balance)}}</th>

                                </tr>
                        @else
                                <tr>
                                    <td colspan="20"><center>No Record on this warehouse</center></td>
                                </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h5 class="text-center">---- {{$now or ''}} ----</h5>
        </div>
    </div>
</div>