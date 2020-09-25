@extends("layout")
@section("content")
<div class="content">
    <div class="wrapper-1">
        <div class="container">
            <div class="main-title">Return and Exchanges</div>
        </div>
    </div>
   
    <div class="wrapper-2">
        <div class="container">
            <p class="title-1">Change of Mind Returns</p>
            <p style="font-style: italic;">"Change of mind" includes purchases you have made in error eg accidentally ordered the wrong size or colour.</p>
            <p>
                We will accept a change of mind return provided it meets the following guidelines:<br>
                <ul>
                    <li>Return within 3 days</li>
                    <li>Return with proof of purchase</li>
                    <li>Item must be unworn, unwashed, unused with all original tags/labels attached</li>
                    <li>Return must be made within the outlet of purchase</li>
                    <li>Item is not listed as an excluded item as outlined below</li>
                </ul>
            </p>
            <p class="title-2">Excluded items (for a change of mind return):</p>
            <ul>
                <li>Underwear</li>
                <li>Earrings</li>
                <li>Cosmetics</li>
                <li>Voucher</li>
                <li>Sale/Clearance</li>
                <li>Personalised product</li>
            </ul>
            <p class="title-1">Faulty Product Returns</p>
            <p>
                If something is faulty, incorrectly described or different from the sample shown we will provide an exchange or refund provided the item is returned within a reasonable time with proof of purchase.<br><br>

                No receipt no exchange policy.
            </p>
            <p class="title-2">Returns Process (In-Store)</p>
            <p>
                <ol>
                    <li>Take your item(s) to Kolorete Marketing Outlet where you purchased the items you would like to return.</li>
                    <li>Provide the team member in store your unwanted item, proof of purchase.</li>
                    <li>Once the returns policy has been met, we&#39;ll offer an exchange or refund on the spot.</li>
                </ol>
            </p>
            <p class="title-3">Exchanges</p>
            <p>Exchanges for the same item in a different size will not incur any additional charges. If the new item is a different product (including a different colour) and there is a price difference you may need to pay the difference or receive a partial refund. </p>
            <p class="title-3">Refunds</p>
            <p>If you request a refund, the purchase price (excluding delivery charges for online orders) will be refunded to you using the original payment method so if you paid cash we can offer a cash refund.</p>
            <p class="title-2">Returns Process (Online)</p>
            <p>
                If your purchase was made online (including Click & Collect) you may choose to return to our online store via courier. Shipping fee will be shouldered by the customer.<br><br>

                Once received, we'll process the refund and notify you via email. Your refund will be credited back to you in the next 1-5 days, depending on the payment method used to place your order.<br><br>

                If the item returned does not meet our Returns and Exchange Policy above your order will be sent back to you.
            </p>
        </div>
    </div>
    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/terms_and_conditions.css">
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/responsive.css">
@endsection

@section("script")

<script type="text/javascript">
$(document).ready(function($) {
    
        /*TEXT FADEOUT*/
        $(window).scroll(function(){
                $(".caption-container, .caption-logo-container").css("opacity", 1 - $(window).scrollTop() / 250);
        });

        //START MISSION AND VISION
        $(".title-vision").click(function()
        {
            $("#vision").removeClass("hide");
            $("#mission").addClass("hide");
            $(".title-vision").addClass("highlighted");
            $(".title-mission").removeClass("highlighted");
            
        });
        $(".title-mission").click(function()
        {
            $("#vision").addClass("hide");
            $("#mission").removeClass("hide");
            $(".title-mission").addClass("highlighted");
            $(".title-vision").removeClass("highlighted");
        });
        //END MISSION ANF VISION
});
</script>

@endsection