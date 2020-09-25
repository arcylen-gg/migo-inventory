 <div class="payment-list row clearfix">
    @if(count($_payment) > 0)
        @foreach($_payment as $payment)
        <li class="payment-li">
            <div class="form-group">
                <div class="col-sm-1">
                   <a href="javascript:" class="remove-payment" payment-id={{$payment->cart_payment_id}}> <i class="fa fa-times-circle" style="color:red"></i></a> 
                </div>
                <div class="col-sm-4">
                    {{strtoupper($payment->payment_type)}}
                </div>
                <div class="col-sm-7 text-right">
                    {{currency('PHP',$payment->payment_amount)}}
                </div>
                <input type="hidden" name="payment_method[]" value="{{$payment->payment_type}}">
                <input type="hidden" value="{{$payment->payment_amount}}" class="compute-payment-amount" name="payment_amount[]">
            </div>
        </li>
        @endforeach
    @else
    <li class="text-center">No Payment Selected</li>
    @endif
    <!-- <li>
>>>>>>> mod_arc_pos2
        <div class="col-sm-1">
           <a href="javascript:"> <i class="fa fa-times-circle" style="color:red"></i></a> 
        </div>
        <div class="col-sm-4">
            Check
        </div>
        <div class="col-sm-7 text-right">
            PHP49.00
        </div>
<<<<<<< HEAD
    </li>
</div>
=======
    </li> -->
</div>
<style type="text/css">
    .pos-payment
    {
        padding: 10px;
        border-top: #cccccc 1px dotted;
        border-bottom: #cccccc 1px dotted;
    }
    .payment-list li
    {
        list-style: none;
        padding: 5px;
        margin: 5px; 
    }
</style>