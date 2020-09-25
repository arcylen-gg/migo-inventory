<div data-page="order-details" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="/members/order" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Order Details</div>
            {{-- <div class="right"><a href="#" data-popover=".popover-profile" class="link open-popover"><img class="menu-button" src="/themes/{{ $shop_theme }}/assets/mobile/img/menu.png"></a></div> --}}
        </div>
    </div>
    <div class="page-content">
        <div class="view-order-details">
            <div class="form-group text-center">
                <h3><b>{{$shop_key or ''}}</b></h3>
                <h4>{{$shop_address or ''}}</h4>
            </div>
            <div class="form-group">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="">Customer Name : </label> {{ucwords($customer_name)}} <br>
                    <label>Transaction Number :</label> {{$list->transaction_number}}<br>
                    <label>Payment Method : </label> {{ strtoupper($list->payment_method) }}
                </div>
                <div class="col-25 text-right">
                    <label>Date :</label> <br>
                    <label>Due Date :</label>
                </div>
                <div class="col-25">
                    {{date('M d, Y',strtotime($list->transaction_date))}} <br>
                    {{date('M d, Y',strtotime($list->transaction_due_date))}}
                </div>
            </div>
            <div class="form-group">
                &nbsp;
            </div>
            <div>
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Item Details</th>
                                    <th>Item Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_item as $key => $item)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td class="text-left">
                                        <b>{{$item->item_name}}</b>
                                    </td>
                                    <td>{{currency('PHP',$item->item_price)}}</td>
                                    <td>{{number_format($item->quantity)}}</td>
                                    <td>{{currency('PHP',$item->subtotal)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan='4' class="text-right"><b>TOTAL</b></td>
                                <td class="text-center">{{currency('PHP', $list->transaction_total)}}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>