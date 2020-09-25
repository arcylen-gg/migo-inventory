<!-- BROWN POPUP KIT -->
<div class="popup-return-policy">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">RETURN POLICY AND PROCEDURE</h4>
        </div>
        <div class="modal-body">
            <div class="policy">
                {!! get_content($shop_theme_info, "terms_and_conditions", "refund_and_return_policy") !!}
            </div>
        </div>
        <div class="modal-footer">
             <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
             <button type="button" id="proceed" class="btn btn-proceed" data-dismiss="modal">Proceed</button>
        </div>
    </div>
</div>

<script>
    $(".btn-proceed").unbind('click');
    $(".btn-proceed").bind('click', function()
    {
        var province = $(".province-is-empty").val();
        var payment_method = $(".payment-method-is-empty").val();
        var shipping_address = $(".textarea-is-empty").val();

        if (province == "")
        {
            alert("Please select your province.");
        }

        else if(shipping_address == "")
        {
            alert("Please type your complete address");
        }

        else if(payment_method == "")
        {
            alert("Please select a payment method.");
        }

        else
        {
            $('.submitt-asd').submit();
        }

    });
</script>