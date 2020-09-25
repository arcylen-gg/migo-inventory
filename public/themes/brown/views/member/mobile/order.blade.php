<div data-page="order" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="/members" class="back link icon-only" data-reload="true" data-ignore-cache="true"><i class="icon icon-back"></i></a></div>
            <div class="center">Orders</div>
            {{-- <div class="right"><a href="#" data-popover=".popover-profile" class="link open-popover"><img class="menu-button" src="/themes/{{ $shop_theme }}/assets/mobile/img/menu.png"></a></div> --}}
        </div>
    </div>
    <div class="page-content">
        <div class="order-view">
            @if(count($_order) > 0)
                @foreach($_order as $key => $order)
                    <div class="holder">
                        <div class="img"><img src="/themes/brown/img/product-placeholder.png"></div>
                        <div class="name">{{ $order->transaction_number }}</div>
                        <p class="buttons-row">
                          <a href="/members/order-details/{{ $order->transaction_list_id }}" class="button button-fill button-raised">View Order Details</a>
                        </p>          
                        <table>
                            <tr>
                                <td class="detail-label">Payment Status:</td>
                                <td class="detail-value"><b>{{ strtoupper($order->payment_status) }}</b></td>
                            </tr>
                            <tr>
                                <td class="detail-label">Delivery Status:</td>
                                <td class="detail-value"><b>{{ strtoupper($order->order_status) }}</b></td>
                            </tr>
                            <tr>
                                <td class="detail-label">Customer Email:</td>
                                <td class="detail-value">{{ $order->email }}</td>
                            </tr>
                            <tr>
                                <td class="detail-label">Tracking Number:</td>
                                <td class="detail-value">NO TRACKING NUMBER YET</td>
                            </tr>
                            
                            <tr>
                                <td class="detail-label">Payment Method:</td>
                                <td class="detail-value"><b>{{ strtoupper($order->payment_method) }}</b></td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            @else
                <div class="text-center" style="padding: 100px;">You don't have any order yet.</div>
            @endif
        </div>
    </div>
</div>