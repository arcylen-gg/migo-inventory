<form class="global-submit" method="post" action="/member/cashier/commission_calculator/create-submit">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal">×</button>
    	<h4 class="modal-title">Commission Calculator</h4>
    </div>
    <div class="modal-body clearfix">
    	<div class="row">
            <div class="clearfix modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-6">
                            <strong>Select Customer</strong>
                            <select class="select-customer form-control input-sm" name="customer_id">

                                @include('member.load_ajax_data.load_customer',['add_search' => ""])
                            </select>
                        </div>
                        <div class="col-md-6">
                            <strong>Customer Email</strong>
                            <input type="text" class="form-control input-sm customer-email" name="customer_email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <strong>Select Agent</strong>
                            <select class="select-agent form-control input-sm" name="agent_id">
                                @include('member.cashier.sales_agent.load_sales_agent')
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-6">
                                <strong>Start Date</strong>
                                <input type="text" class="form-control input-sm datepicker" name="date" value="{{date('m/d/Y')}}">
                            </div>
                            <div class="col-md-6">
                                <strong>Due Date</strong>
                                <input type="text" class="form-control input-sm datepicker" name="due_date">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <strong>Select Property</strong>
                            <select class="select-property form-control input-sm" name="item_id">
                                 @include("member.load_ajax_data.load_item_category",['add_search' => ''])
                            </select>
                        </div>
                        <div class="col-md-6">
                            <strong>Total Selling Price</strong>
                            <input type="text" class="number-input form-control input-sm sales-price text-right compute-all" name="total_selling_price">
                        </div>
                    </div>                    
                    <div class="form-group">
                        <div class="col-md-6">
                            <strong>Downpayment</strong>
                            <input type="text" class="form-control input-sm text-right downpayment compute-all" name="downpayment_percent" value="15%">
                        </div>
                        <div class="col-md-6">
                            <strong>Amount of Downpayment</strong>
                            <input type="text" class="form-control text-right input-sm amount-downpayment" name="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 text-right">
                            <strong>Discount</strong>
                        </div>
                        <div class="col-md-6">
                            <input type="text" value="0.00" class="form-control input-sm text-right discount discount-auto-add-comma" name="discount">
                        </div>
                    </div>
                </div>
                <div style="border: 1px solid #000;margin: 10px;"></div>
                <div class="form-horizontal">
                    <div class="form-group text-center">
                        <div class="col-md-6 text-right">
                            <h4><b>Net Downpayment :</b></h4>
                        </div>
                        <div class="col-md-6">
                            <a id="popover_downpayment" data-trigger="hover" data-placement="top" href="javascript:"><h4><div class="amount-net-downpayment">Amount of NDP</div></h4></a>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-md-6">
                            <select class="select-term text-right form-control input-sm" name="monthly_amort">
                                @for($i = 1; $i <= 30; $i++)
                                <option value="{{$i}}" {{$i == 1 ? 'selected' : ''}}>{{$i}} Month{{$i == 1 ? '' : 's'}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 text-center">
                            <h4><div class="amount-monthly-amort-dp">Monthly Amort of DP</div></h4>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 padding-top-here">
                            <div class="col-md-6">
                                <strong>Miscellaneous Fee</strong>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control text-right input-sm misc compute-all" name="misceleneous_fee_percent" value="5%">
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <h4><div class="amount-misc">Miscellaneous Amount</div></h4>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 text-right">
                            <h4><b>Loanable Amount :</b></h4>
                        </div>
                        <div class="col-md-6 text-center">
                            <h4><div class="amount-loanable">Loanable Amount</div></h4>
                            <input type="hidden" class="input-loanable-amount" name="loanable_amount">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 text-right">
                            <h4><b>Total Contract Price :</b></h4>
                        </div>
                        <div class="col-md-6 text-center">
                            <h4><div class="amount-tcp">TCP Amount</div></h4>
                            <input type="hidden" class="input-tcp" name="total_contract_price">
                        </div>
                    </div>
                </div>
                <div style="border: 1px solid #000;margin: 10px;"></div>
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-6 text-right">
                            <h4><b>Total Commission :</b></h4>
                        </div>
                        <div class="col-md-6 text-center">
                            <input type="hidden" name="total_commission" class="input-tc">
                            <a id="popover_tc" data-trigger="hover" data-placement="top" href="javascript:"><h4><div class="amount-tc">TC Amount</div></h4></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 padding-top-here">
                            <div class="col-md-6">
                                <strong>NDP Commission</strong>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-sm text-right compute-all ndp-commission" value="60%" name="ndp_commission">
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <h4><div class="amount-ndp">NDP Commission Amount</div></h4>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 padding-top-here">
                            <div class="col-md-6">
                                <strong>TCP Commission</strong>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="change-tcp form-control input-sm text-right compute-all tcp-commission" name="tcp_commission" value="40%">
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <h4><div class="amount-tcp1">TCP Commission Amount</div></h4>
                        </div>
                    </div>
                </div>
            </div>
    	</div>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    	<button class="btn btn-primary btn-custom-primary" type="submit">Save</button>
    </div>
</form>
<div class="hidden row clearfix" id="computation-content">
    <div style="width: 300px">
        <div class="col-md-7 text-center">
            <div>TSP - DISCOUNT </div>
            <div style="border: 0.5px solid #000"></div>
            <div><label class="c-amount-tax">Tax Exclu</label></div> 
        </div>
        <div class="col-md-5">
            <div style="padding-top:10px">* Comm.</div>
        </div>
        <br>
        <div class="col-md-7 text-center">
            <div><label class="c-amount-tsp">1,000,000</label> - <label class="c-amount-disc">10,000</label> </div>
            <div style="border: 0.5px solid #000"></div>
            <div><label class="c-amount-tax">1.12</label></div>
        </div>
        <div class="col-md-5">
            <div style="padding-top:10px">* <label class="c-amount-commission">8%</label></div>
        </div>
    </div>
</div>
<div class="hidden row clearfix" id="downpayment-content">
    <div class="text-center">

        <div class="col-md-12">
            <div>(TSP - (DP * TSP)) - DISC</div>
        </div>
        <br>
        <br>
        <div class="col-md-12">
            <div>(<label class="c-amount-tsp">1,000,000</label> - (<label class="c-amount-dp">15%</label> * <label class="c-amount-tsp">1,000,000</label>)) - <label class="c-amount-disc">10,000</label> </div>
        </div>
    </div>
</div>
<script type="text/javascript">

  function setTwoNumberDecimal(x) 
    {
        var value = parseFloat($(x).val()).toFixed(2);
        $(x).val(value);
    }

    $('.number-input').change(function(e)
    {
        setTwoNumberDecimal(e.currentTarget);
    });



    $('#popover_tc').popover({ 
        html : true,
        title: '<h4 style="padding:0px">Commission Computation</h4>',
        content: function() {
          return $("#computation-content").html();
        }
    });
    $('#popover_downpayment').popover({ 
        html : true,
        title: '<h4 style="padding:5px">Net Downpayment Computation</h4>',
        content: function() {
          return $("#downpayment-content").html();
        }
    });
</script>
<style type="text/css">
    .padding-top-here
    {
        padding-top: 7px;
    }
</style>
<script type="text/javascript" src="/assets/member/js/create_commission_calculator.js"></script>

<link rel="stylesheet" type="text/css" href="/assets/member/css/item_add_v2.css">