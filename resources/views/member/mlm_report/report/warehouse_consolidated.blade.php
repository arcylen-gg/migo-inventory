    <table class="table table-condensed table-bordred">
                            <tr>
                                <td>Warehouse Sales Report</td>
                            </tr>
                        <tr>
                            <td>from:</td>
                            <td>{{$from}}</td>
                        </tr>
                        <tr>
                            <td>to:</td>
                            <td>{{$to}}</td>
                        </tr>
      
      </table>


      <br>
      <br>
    
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div> 
<?php $grand_total = 0; ?>
@foreach($sales as $key => $value)
<?php 
    $sub_total = 0;
?>
<div class="panel panel-default panel-block panel-title-block col-md-4" id="top">
    <div class="panel-heading">
        <div>
            
            <table class="table table-condensed table-bordred">
                <th>{{$value}}</th>
                <th>Amount</th>
    
                
                @foreach($warehouse as $w_key => $w_val)
                    @if($key == 'e_wallet_transfer' || $key == 'e_wallet_tours')
                        @if($w_val->main_warehouse == 1)
                            <tr>
                                <td>PHILTECH MAIN OFFICE</td>
                                <td>{{currency('PHP', $w_val->$key)}}</td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td>{{$w_val->warehouse_name}}</td>
                            <td>{{currency('PHP', $w_val->$key)}}</td>
                        </tr>
                     @endif
                    <?php 
                        $sub_total += $w_val->$key; 
                    ?>
                @endforeach
                <tr>
                    <td><span class="pull-right"><small>SUBTOTAL:</small></span></td>
                    <td>{{currency('PHP', $sub_total)}}</td>
                <?php 
                    $grand_total +=  $sub_total;
                ?>
                </tr>
                
            </table>
            
        </div>
    </div>
</div>   
      
@endforeach
<div class="panel panel-default panel-block panel-title-block col-md-12" id="top">
    <div class="panel-heading">
        <div>
            <table class="table">
                <tr>
                    <th><span class="pull-right">Grand Total: </span></th>
                    <th>{{currency('PHP', $grand_total)}}</th>
                </tr>
                <tr>
                    <th><span class="pull-right">Overall Grand Total: </span></th>
                    <th>{{currency('PHP', $g_over_total)}}</th>
                </tr>
            </table>
        </div>
    </div>
</div>   