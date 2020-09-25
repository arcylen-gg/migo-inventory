<!-- @url  -->
@foreach($_payment_method as $key=>$payment)
	<option value="{{$payment->method_id}}" {{ isset($payment_method_id) ?  $payment_method_id == $payment->method_id ? 'selected' : '' : '' }}>{{$payment->method_name}} </option>
@endforeach
<option class="hidden" value="" />