<!-- <div class="col-md-12 col-sm-6 col-xs-12 hide">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-cart-arrow-down"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Product Sales Report</span>
      <span class="info-box-number">Per Item</span>
    </div>
  </div>
</div> 
<br>
<br>
<br>
<br>
<br>
<div class="panel panel-default panel-block panel-title-block hide" id="top">
    <div class="panel-heading">
        <div>
        	<div style="overflow-x:auto;">
        		<table class="table table-condensed table-bordered">
        			<thead>
        				<thead>
                            <tr>
                                <th>Item Name</th>  
                                @foreach($filter as $key => $value)  
                                    <th>{{$key}}</th> 
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventory as $key => $value)
                            <tr>
                                <td>{{$key}}</td>
                                @foreach($value as $key2 => $value2)
                                <td>
                                    {{$value2}}
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
        			</thead>
        			<tbody>
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>    


<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-cart-arrow-down"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">Product Sales Report</span>
      <span class="info-box-number">Per Invoice</span>
    </div>
  </div>
</div> 
<br>
<br>
<br>
<br>
<br> -->
@foreach($warehouse as $warehouse_key => $warehouse)
@foreach($userss as $user_key => $user_a)


<?php $count_transaction = 0; ?>
@foreach($invoice as $key => $value)
    @if($value->warehouse_id == $warehouse->warehouse_id)
        @if($value->user_id == $user_a->user_id)
        <?php $count_transaction += 1; ?>
        @endif
    @endif 
@endforeach

@if($count_transaction > 1)
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <style type="text/css">
            .Container
            {

            }
            .Content
            {
                transform:rotateX(180deg);
                -ms-transform:rotateX(180deg); /* IE 9 */
                -webkit-transform:rotateX(180deg); /* Safari and Chrome */
            }

            .Flipped, .Flipped .Content
            {
                transform:rotateX(180deg);
                -ms-transform:rotateX(180deg); /* IE 9 */
                -webkit-transform:rotateX(180deg); /* Safari and Chrome */
            }
            </style>
            <div style="overflow-x:auto;" class="table-responsive Container Flipped">

                <table class="table table-condensed table-bordered Content" style="font-size: 8px;">
                    <thead>
                        <thead>
                            <tr>
                                <th colspan="12"><center><h2>CASHIER'S END SESSION REPORT</h2></center></th>
                            </tr>
                            @if(isset($warehouse))
                            <tr>
                                <td>Branch / Warehouse Name</td>
                                <td>{{$warehouse->warehouse_name}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td>{{$filteru['from']}}</td>
                                <td>{{$filteru['to']}}</td>
                            </tr>
                            <tr>
                                <th>V.I.P. Name</th>
                                <th>Type of Membership</th>  
                                <th>Slot</th>
                                <th>Email</th>
                                <th>Item Name</th>
                                <th>SRP</th>
                                <th>VIP Price</th>
                                <th>Quantity</th>
                                
                                <th>Amount</th>
                                <th>Serial</th>

                                <th>Payment Type</th>
                                <th>Time of Transaction</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $t_niggs = 0; ?>
                            @foreach($invoice as $key => $value)
                            @if($value->warehouse_id == $warehouse->warehouse_id)
                            @if($value->user_id == $user_a->user_id)
                                <tr>
                                    <td>{{name_format_from_customer_info($value)}}</td>
                                    <td>{{$value->membership_name}}</td>
                                    <td>{{$value->slot_no}}</td>
                                    <td>{{$value->item_code_customer_email}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                    
                                    </td>
                                    <td></td>
                                </tr>
                                @if(isset($items_unfiltered[$key]))
                                @foreach($items_unfiltered[$key] as $key2 => $items2)
                                <tr>
                                    <td colspan="4"></td>
                                    <td>{{$items2->item_name}}</td>
                                    <td>{{currency('PHP', $items2->item_price)}}</td>
                                    <td>{{currency('PHP', $items2->item_membership_discounted)}}</td>
                                    <td>{{$items2->item_quantity}}</td>
                                    <td>{{currency('PHP', $items2->item_membership_discounted * $items2->item_quantity)}}</td>
                                    <td>{{$items2->item_serial != null ? $items2->item_serial : 'N/A'}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endforeach
                                @endif
                                <tr>
                                    <td colspan="8"></td>
                                    <td>{{currency('PHP', $value->item_total)}}</td>
                                    <?php $t_niggs += $value->item_total; ?>
                                    <td></td>
                                    <td>
                                        <?php 
                                        switch ($value->item_code_payment_type) {
                                            case 1:
                                                echo 'CASH';
                                                break;
                                            case 2:
                                                echo 'GC';
                                                break;
                                            case 3:
                                                echo 'Wallet';
                                                break;
                                            
                                            default:
                                                echo 'CASH';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td>{{$value->item_code_date_created}}</td>
                                </tr>
                            @endif
                            @endif    
                            @endforeach
                            <tr>
                                <td colspan="8">Total Sales</td>
                                <td>{{currency('PHP', $t_niggs)}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </thead>
                </table>
            </div>



            <div style="overflow-x:auto;" class="table-responsive">

                <table class="table table-condensed table-bordered" style="font-size: 8px;">
                <tbody>
                    <tr>
                        <td colspan="2">Breakdown of Payment</td>
                    </tr>
                
                    @if(isset($payment))
                        @foreach($payment as $key => $value)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$value}}</td>
                        </tr>
                        @endforeach
                    @endif
                    @if(isset($user_a))
                        <tr>
                            <td>Reported by:</td>
                            <td>{{$user_a->user_first_name}} {{$user_a->user_last_name}}</td>
                        </tr>
                    @endif
                </tbody>
                
                </table>
            </div>


        </div>
    </div>
</div> 
@endif 

@endforeach
@endforeach
