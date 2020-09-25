<!-- @url '/member/customer/load_customer' -->
@if(count($_customer) > 0)
@foreach($_customer as $key => $customer)
	<option value="{{$customer->customer_id}}" ctr-si-ref-num="{{$customer->ctr_si_refnum}}" ctr-so-ref-num="{{$customer->ctr_so_refnum}}" ctr-ref-num="{{$customer->ctr_rp_refnum}}" ctr-si="{{$customer->ctr_si}}" ctr-sr="{{$customer->ctr_sr}}" ctr-wis="{{$customer->ctr_wis}}" ctr-rp="{{$customer->ctr_rp}}" billing-address="{{$customer->billing_address ? $customer->billing_address : $customer->customer_street}}" salesrep_id="{{$customer->agent_id}}" adjusted_mb="{{currency('PHP ',$customer->adjusted_monthly_budget)}}" salesrep="{{$customer->salesrep_fname.' '.$customer->salesrep_mname.' '.$customer->salesrep_lname}}" email="{{$customer->email}}" monthly-budget="{{$customer->fix_monthly_budget}}" previous-budget="{{$customer->previous_budget['amount']}}" previous-month="{{$customer->previous_budget['month']}}" adjusted-monthly-budget="{{$customer->adjusted_monthly_budget}}" {{ isset($customer_id) ? ($customer_id == $customer->customer_id ? 'selected' : '') : '' }}
		>{{$customer->company != "" ? $customer->company : ucwords($customer->title_name.' '.$customer->first_name.' '.$customer->middle_name.' '.$customer->last_name) }}</option>
	@if(sizeOf($_customer)-1 == $key)
		<option class="hidden" value="" />
	@endif
@endforeach
@else
<option>No Customer</option>
@endif