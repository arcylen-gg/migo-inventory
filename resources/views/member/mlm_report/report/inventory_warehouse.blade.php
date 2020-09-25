
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
            @foreach($warehouse_a as $k => $warehouse)
            <div style="overflow-x:auto;" class="table-responsive Container Flipped">
                
                <table class="table table-condensed table-bordered Content" style="width: 100%; font-size: 8px;">
                    <thead>
                        <thead>
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
                            @if(isset($warehouse_per_invoice[$k]))
                            @foreach($warehouse_per_invoice[$k] as $key => $value)
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

                            @endforeach
                            @endif
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
                
                    @if(isset($payment[$k]))
                        @foreach($payment[$k] as $key => $value)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$value}}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
                
                </table>
            </div>
            @endforeach
            
            <div style="overflow-x:auto;" class="table-responsive">

                <table class="table table-condensed table-bordered" style="font-size: 8px;">
                <tbody>
                    <tr>
                        <td colspan="2">Grand Total Sales (Date filtered)</td>
                    </tr>
                
                    @if(isset($payment_all))
                        @foreach($payment_all as $key => $value)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$value}}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
                
                </table>
            </div>
        </div>
    </div>
</div>    