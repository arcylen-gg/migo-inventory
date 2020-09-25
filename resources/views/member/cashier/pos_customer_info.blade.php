@if(!isset($exist))
<div class="row">
    <div class="col-md-12">
        <div class="input-group">
            <span style="background-color: #eee" class="input-group-addon customer-button-scan" id="basic-addon1">
                <i class="fa fa-user customer-scan-icon"></i>
                <i style="display: none;" class="fa fa-spinner fa-pulse fa-fw customer-scan-load"></i>
            </span>
            <input type="text" class="form-control event_search_customer" placeholder="Enter Customer Name" aria-describedby="basic-addon1">
        </div>
        <div class="pos-search-container-customer"></div>
    </div>
</div>
@else
<div class="row" style="padding: 15px">
    <div class="col-md-4 avatar-container">
        <img class="cashier-avatar" src="/assets/member/images/user.png">
    </div>
    <div class="col-md-8">
        <div class="customer-name text-bold">{{strtoupper($exist['customer']->first_name.' '.$exist['customer']->middle_name.' '.$exist['customer']->last_name)}}</div>
        <div class="customer-name">
            <div class="row">
                <div class="col-md-5">Customer</div>
                <div class="col-md-7">{{$exist['customer']->customer_id}}</div>
            </div>
            <div class="customer-name">
                <div class="row">
                    <div class="col-md-5">Phone</div>
                    <div class="col-md-7">{{isset($exist['other']->customer_mobile) ? $exist['other']->customer_mobile : '- - -'}}</div>
                </div>
            </div>
            <div class="customer-name">
                <div class="row">
                    <div class="col-md-5">Balance</div>
                    <div class="col-md-7">PHP 0.00</div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(count($exist['_slot']) > 0)
<div class="row" style="padding: 15px">
    <div class="col-md-12" >
        <div class="customer-name">
            <div class="row clearfix">
                <div class="col-md-5">Slot No</div>
                <div class="col-md-7">
                    <select name="slot_id" class="form-control input-sm change-slot-id">
                        @foreach($exist['_slot'] as $slot)
                            <option value="{{$slot->slot_id}}">{{$slot->slot_no}}</option>
                        @endforeach
                    </select>
                </div>
            </div>                
        </div>
    </div>
    <div class="col-md-12">
        <div class="customer-name">
            <div class="row">
                <div class="col-md-5">Current Wallet</div>
                <div class="col-md-7 text-right">{{currency('PHP ',$customer_points['total_wallet'])}}</div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="customer-name">
            <div class="row">
                <div class="col-md-5">Total GC</div>
                <div class="col-md-7 text-right">{{currency('',$customer_points['total_gc'])}} POINT(S)</div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="customer-name">
            <div class="row">
                <div class="col-md-5">Current Slot GC</div>
                <div class="col-md-7 text-right current_gc">{{currency('',$customer_points['total_gc'])}} POINT(S)</div>
            </div>
        </div>
    </div>
</div>
@endif
<div class="row pos-customer-action-button text-center" style="padding: 5px">
    <div class="col-md-6">
        <a class="btn btn-custom-white full-width popup" size="lg" link="/member/customer/customeredit/{{$exist['customer']->customer_id}}"><i class="fa fa-edit"></i> Update Customer</a>
    </div>
    <div class="col-md-6">
        <a class="btn btn-custom-white full-width detach-customer"><i class="fa fa-close"></i> Detach</a>
    </div>
</div>
@endif

<script type="text/javascript">
    $(document).ready(function()
    {
        action_loader();
        action_load_current_slot_gc();
        $('.change-slot-id').change(function()
        {
            action_loader();
            action_load_current_slot_gc();
        });
        function action_loader()
        {
            $(".current_gc").html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
        }
        function action_load_current_slot_gc()
        {
            $.ajax(
            {
                url: "/member/cashier/pos/get_current_gc?slot_no="+$('.change-slot-id').val(),
                type: "get",
                success: function(data)
                {
                    $('.current_gc').html(data+" POINT(S)");
                }
            });
        }
    });
</script>