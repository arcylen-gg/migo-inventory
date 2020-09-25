<table style="table-layout: fixed;" class="table table-condensed table-bordered table-sale-month">
    <thead>
        <tr>
            <th class="text-left">Company Name</th>
            <th class="text-left">Contact Person</th>
            <th class="text-left">Contact Details</th>
            @if($migo_customization)
            <th class="text-center">Category</th>
            <th class="text-center">Type</th>
            @endif
            @if($monthly_budget)
            <th class="text-center">Fix Monthly Budget</th>
            @endif
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_customer) > 0)
            @foreach($_customer as $customer)
             <tr class="cursor-pointer" id="tr-customer-{{$customer->customer_id}}" style="color: {{$customer->approved == 1? '#000' : '#ff3333' }};">
                <td class="text-left">
                    {{$customer->company}}
                </td>
                <td class="text-left">
                    {{$customer->title_name.' '.$customer->first_name.' '.$customer->middle_name.' '.$customer->last_name.' '.$customer->suffix_name}}
                </td>
                <td class="text-left">
                    Tel No: {{$customer->customer_phone != null ? $customer->customer_phone : 'No Phone Number' }}<br> 
                    Mobile: {{$customer->customer_mobile != null ? $customer->customer_mobile : 'No Mobile Number'}} <br>
                    @if($migo_customization)
                        Address : {{$customer->customer_street . " " .$customer->customer_city}}
                    @else
                        Email Address : <a target="_blank" {{$customer->email != "" ? 'href=https://mail.google.com/mail/?view=cm&fs=1&to='.$customer->email : '' }}>{{$customer->email != "" ? $customer->email : "---" }}
                    @endif
                </td>
                @if($migo_customization)
                <td class="text-left ">{{ucwords(str_replace('-',' ',$customer->customer_category))}}</td>
                <td class="text-left ">{{strtoupper(str_replace('-',' ',$customer->customer_category_type))}}</td>
                @endif
                @if($monthly_budget)
                <td class="text-right"><span>{{currency("PHP ",$customer->fix_monthly_budget)}}</span></td>
                @endif
                <td class="text-center">
                    <!-- ACTION BUTTON -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-custom">
                        <li><a target="_blank" href="/member/transaction/receive_payment/create?c_id={{$customer->customer_id}}"> Receive Payment</a></li>
                        <li><a target="_blank" href="/member/transaction/sales_invoice/create?c_id={{$customer->customer_id}}"> Create Sales Invoice</a></li>
                        <li><a target="_blank" href="/member/transaction/sales_receipt/create?c_id={{$customer->customer_id}}"> Create Sales Receipt</a></li>
                        <li><a target="_blank" href="/member/transaction/estimate_quotation/create?c_id={{$customer->customer_id}}"> Create Estimate and Quotation</a></li>
                        <li><a target="_blank" href="/member/transaction/sales_order/create?c_id={{$customer->customer_id}}"> Create Sales Order</a></li>
                        <li><a href="javascript:" class="active-toggle" data-content="{{$customer->customer_id}}" data-target="#tr-customer-{{$customer->customer_id}}" data-value="{{$customer->customer_archived}}" data-html="{{$customer->customer_archived == 0? 'inactive':'active'}}">{{$customer->customer_archived == 0? 'Make Inactive':'Make active'}}</a></li>
                        <li><a href="javascript:" class="popup" link="/member/customer/customeredit/{{$customer->customer_id}}" size="lg" data-toggle="modal" data-target="#global_modal">Edit Customer Info</a></li>
                        <li><a href="/member/customer/details/{{$customer->customer_id}}" target="_blank">View transactions</a></li>
                      </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        @else
            <tr><td  colspan="{{$monthly_budget ? 7 : 6}}" class="text-center"> NO CUSTOMER </td></tr>
        @endif
    </tbody>
</table>
<div class="padding-10 text-center">
    {!!$_customer->appends(Request::capture()->except('page'))->render()!!}
</div>