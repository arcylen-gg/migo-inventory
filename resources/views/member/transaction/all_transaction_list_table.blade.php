<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th class="text-center">Transaction Number</th>
            <th class="text-center">Customer Name</th>
            <th class="text-center">Total Amount</th>
            <th class="text-center"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_list) > 0)
        @foreach($_list as $key => $list)
            <tr>
                <td class='text-center'>{{$list->transaction_number}}</td>
                <td class='text-center'>{{$list->customer_name}}</td>
                <td class='text-center'>{{currency('PHP',$list->transaction_total)}}</td>
                <td class='text-center'><a class="popup" size="md" link="/member/cashier/transactions/view_item/{{$list->transaction_list_id}}">View</a></td>
            </tr>
        @endforeach
        @else
            <tr>
                <td colspan='5' class='text-center'>No Transaction Yet</td>
            </tr>
        @endif
    </tbody>
</table>
{{ $_list->render() }}