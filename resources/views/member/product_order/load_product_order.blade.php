<div class="table-responsive">
    <table class="table table-condensed">
        <thead style="text-transform: uppercase">
            <tr>
                <th>Order</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Payment Status</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if($ec_order)
                @foreach($ec_order as $order)
                <tr>
                    <td>{{$order->ec_order_id}}</td>
                    <td>{{$order->created_date}}</td>
                    <td>{{$order->first_name}} {{$order->middle_name}} {{$order->last_name}}</td>
                    <td>{{$order->order_status}}</td>
                    <td>{{$order->total}}</td>
                    <th>
                        <div class="btn-group">
                            <a class="btn btn-primary btn-grp-primary" href="/member/ecommerce/product_order/create_order?id={{$order->ec_order_id}}">View</a>
                        </div>
                    </th>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5"><center>No Order</center></td>    
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="text-center pull-right">
    {!!$ec_order->render()!!}
</div>