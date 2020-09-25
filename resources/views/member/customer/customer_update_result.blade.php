<td class="text-left">{{$customer->title_name.' '.$customer->first_name.' '.$customer->middle_name.' '.$customer->last_name.' '.$customer->suffix_name}}</td>
<td class="text-left">{{$customer->customer_phone}}</td>
<td class="text-left">{{$customer->email}}</td>
<td class="text-right  {{$customer->customer_opening_balance > 0? 'color-red': ''}}"><span class="pull-left">PHP</span>{{number_format($customer->customer_opening_balance,2)}}</td>
<td class="text-center">
    <!-- ACTION BUTTON -->
    <div class="btn-group">
      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action <span class="caret"></span>
      </button>
      <ul class="dropdown-menu dropdown-menu-custom">
        <li><a href="/member/customer/receive_payment?customer_id={{$customer->customer_id1}}">Receive Payment</a></li>
        <li><a href="/member/customer/invoice?customer_id={{$customer->customer_id1}}">Create Invoice</a></li>
        <li><a href="/member/customer/sales_receipt">Create Sales Receipt</a></li>
        <li><a href="/member/customer/transaction_list">Transaction List</a></li>
        <li><a href="/member/customer/details/{{$customer->customer_id1}}">View Customer Details</a></li>
        <!-- <li><a href="/member/customer/estimate">Create Estimate</li> -->
        <li><a class="popup" link="/member/customer/customeredit/{{$customer->customer_id1}}" size="lg" data-toggle="modal" data-target="#global_modal">Edit Customer Info</a></li>
        <li><a class="active-toggle" data-content="{{$customer->customer_id1}}" data-target="#tr-customer-{{$customer->customer_id1}}" data-value="0" data-html="inactive">Make Inactive</a></li>
      </ul>
     
    </div>
</td>