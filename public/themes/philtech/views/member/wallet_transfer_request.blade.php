<form class="global-submit" role="form" action="/members/wallet-transfer" method="post">
    {{ csrf_field()  }}
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h4 class="modal-title redeemable-add-page-title">{{$page}}</h4>
    </div>
    <div class="modal-body clearfix">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-horizontal">

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Slot</label>
                            <select name="slot" class="form-control slot-owner" style="width:100;">
                            	@foreach($slot_owner as $owner)
                            	<option class="form-control" value="{{$owner->slot_no}}">{{$owner->slot_no}}</option>
                            	@endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="text-left col-md-6">
                            <label class="transfer-fee" >Transfer fee: 0</label>
                        </div>
                        <div class="text-right col-md-6">
                            <label class="current_wallet" >Current Wallet: 0</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Amount</label>
                            <input type="number" id="basic-input" class="form-control amount" min="1" name="amount">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="basic-input">Recipient</label>
                            <input id="basic-input" name="recipient" class="form-control search-recipient" placeholder="Slot no" autocomplete="off">
                            <div class="load-prediction-here">
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal"><span class="fa fa-close" /> Close</button>
        <button class="btn btn-primary btn-custom-primary send" type="submit"><span class="fa fa-money" />  Send</button>
    </div>
</form>

<script type="text/javascript">
function confirm_transfer(data)
{
    var recipient = $('.search-recipient').val();
    var slot = $('.slot-owner').val();
    var amount = $('.amount').val();

    if(confirm("Are you sure you want to transfer "+amount+" to "+recipient+"?"))
    {
        $.ajax(
        {
            url: "/members/send-transfer",
            data: {"recipient":recipient,"slot":slot,"amount":amount},
            type: "get",
            success: function(response)
            {
                if(response == 'error_recipient')
                {
                    error_recipient(data);
                }
                else if(response=='error_slot')
                {
                    error_slot(data);
                }
                else if(response=='error_amount')
                {
                    error_amount(data);
                }
                else
                {
                    success_wallet_transfer(data);
                }
                
            }
        });
    }
    
}
function success_wallet_transfer(data)
{
    toastr.success("Wallet transfer complete");
    data.element.modal("hide");
}
function error(data)
{
    toastr.error("Slot no. does not exist");
}
function error_slot(data)
{
    toastr.error("The slot you are using does not belong to you");
}
function error_amount(data)
{
    toastr.error("Not enough wallet");
}
function error_recipient(data)
{
    toastr.error("You cannot transfer wallet to the same slot");
}

</script>

<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/wallet_transfer.js"></script>