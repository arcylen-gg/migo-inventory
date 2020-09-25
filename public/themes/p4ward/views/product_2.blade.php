@extends("layout")
@section("content")
<div class="content">

    <!-- Media Slider -->
    <div id="home" class="slider-wrapper" style="background-image: url('/themes/{{ $shop_theme }}/img/product-banner.jpg')">
        <div class="caption-container">
            <span class="title-white">Product </span><span class="title-orange">Profile</span>
        </div>
    </div>
    <div class="wrapper-1">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="product-image-holder">
                        <div class="product-container slider-for">
                            <img src="/themes/{{ $shop_theme }}/img/red-rice.jpg">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="title-container">
                        <span class="title-blue">Don Organics </span><span class="title-orange">Red Rice Scrub</span>
                    </div>
                    <div class="subtitle-container">100% Organic</div>
                    <div class="details-container">
                        <p>
                            Peeling regularly is one way of keeping our skin young, hydrated and healthy. This helps in getting rid of
                            dead cells and improving blood circulation.<br><br>

                            Red Rice has exfoliating, antioxidant, anti-inflammatory and emollient properties which can soften the
                            skin, hydrate, and relive inflammations.<br><br>

                            Red Rice has natural anti aging and oil-absorbing properties which makes them good for acne-prone or
                            oily and dull mature skin.<br><br>

                            Don Organics Red Rice Scrub will work wonderfully on you skin. It will exfoliate,
                            purifies and re-mineralize your skin. This body scrub will leave your skin satin smooth, soft and
                            refreshed. This will help improve fine lines, reduce signs of aging and soothe dry skin.
                        </p>
                    </div>
                    <div class="ingredients-container">
                        <div class="title">Ingredients</div>
                        <p>Red Rice</p>
                        <p>Cinnamon</p>
                        <p>Sea salt</p>
                        <p>Cold-pressed Virgin Coconut oil</p>
                        <p>Vitamin E</p>
                        <p>Brown Sugar</p>
                        <p>Raw Honey</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper-2">
        <div class="container">
            <div class="title-container">How to Use</div>
            <div class="procedure-container">
                <div class="row clearfix">
                    <div class="col-md-2 col-md-offset-1">
                        <img src="/themes/{{ $shop_theme }}/img/wrapper2-wet-image.png">
                        <div class="caption">Get Wet</div>
                    </div>
                    <div class="col-md-2">
                        <img src="/themes/{{ $shop_theme }}/img/wrapper2-scoop-image.png">
                        <div class="caption">Scoop</div>
                    </div>
                    <div class="col-md-2">
                        <img src="/themes/{{ $shop_theme }}/img/wrapper2-scrub-image.png">
                        <div class="caption">Scrub</div> 
                    </div>
                    <div class="col-md-2">
                        <img src="/themes/{{ $shop_theme }}/img/wrapper2-mins-image.png">
                        <div class="caption">Wait</div>
                    </div>
                    <div class="col-md-2">
                        <img src="/themes/{{ $shop_theme }}/img/wrapper2-rinse-image.png">
                        <div class="caption">Rinse</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="wrapper-3">
        <div class="container">
            <div class="title-container">Some of its great benefits are:</div>
            <div class="benefits-container">
                <div class="title">1. Reduces Inflammation</div>
                <p>The most common problems in dermatology are inflammatory skin diseases which come in many forms such as occasional rashes with skin itching and redness to chronic conditions such as eczema, rosacea and psoriasis. Coffee has been proven to have anti-inflammatory property and together with antioxidants, it can help soothe red and inflammation in the skin.</p>
                <div class="title">2. Gently exfoliate the skin</div>
                <p>Don Organics coffee scrub has three main ingredients that when combined, becomes a great exfoliator, they are: Finely-ground Robusta coffee, salt and sugar. Don Organics uses sea salt as well as brown sugar mainly because both of these ingredients contain natural minerals and acids that fight off harmful bacteria and are beneficial for the skin. These particles help remove dead skin cells and has been said to stimulate lymph drainage and increase blood flow. As a result, noticeable glowing smoother skin is achieved within few days of using. This can also help in removing toxins.</p>
                <div class="title">3. Rich source of antioxidants</div>
                <p>Our environment, especially in the metro, is full of pollution particles that can damage and irritate our skin. Coffee is rich in antioxidants called polyphenols. Filling up the skin with antioxidants in coffee scrub protects it. It’s proven scientifically that coffee bean extracts can contribute to skin cell energy preservation because of its antioxidant properties. Coffee can fight free radicals and help slow down the process of aging because of DNA damage. In addition to this, coffee can also protect our skin from ultraviolet radiation, giving us a much younger looking skin.</p>
                <div class="title">4. Help Brightens Skin</div>
                <p>As mentioned earlier, coffee is rich in antioxidants which can rejuvenate dull, tired skin by leading needed nutrients into the pores. And with its ability to improve circulation, scrubbing regularly will quickly brighten your complexion.</p>
                <div class="title">5. Relaxing</div>
                <p>If you love the smell of freshly ground coffee, there’s good reason. The scent of coffee acts to clear a person’s mind of excessive thoughts. The scent also works as an anti-depressant and can help combat feelings of nausea. So using a coffee body scrub will allow you to take advantage of these aromatherapy features.</p>
                <div class="title">6. Reduces Cellulite</div>
                <p>Caffeine has the ability to increase circulation which helps smooth the appearance of cellulite. It also contributed to a lipolytic effect on fat cells or in layman’s term, simply breaks down fats. It’s even scientifically proven that applying a topical caffeine solution such as a coffee scrub to the skin can cause a decrease in fatty cell size of 17 percent. Because of this effect, excess fat under the skin tends to even up which help to diminish the appearance of cellulite.</p>
                <div class="title">7. Help Reduce the Appearance of Stretch Marks</div>
                <p>Don Organics Coffee Scrub when applied directly on the skin, it stimulates circulation and the flow of blood. This is very helpful for cell regeneration. The caffeine exfoliates and gets rid of dead skin cells. This gives an opportunity for the new skin cells to receive moisture from olive oil (one of the key ingredients of Don Organics Coffee Scrub). Olive oil protects, cleanses, and moisturizes the skin. It penetrates into the other skin layers to work on them. These combined benefits from olive oil and caffeine contribute in reducing the visibility of stretch marks.</p>
                <div class="title">8. Minimizes Dark Circles under the eyes</div>
                <p>Caffeine increases the mircocirculation of blood in the skin which effectively pushing stagnant blood from dark tired eyes helping it look radiant again.</p>
                <div class="title">9. Lessen Puffiness</div>
                <p>Looking exhausted due to puffiness &amp; swollen eyes can be stressful. Don Organics coffee scrub can help lighten in up. This is because caffeine and other compounds like methylxanthine in coffee have a diuretic effect, which can help in minimizing edema or swollen tissues due to excess water retention.</p>
                <div class="title">10. Hydrates</div>
                <p>This body scrub harnesses the skin softening properties of coconut oil, so you can wave goodbye away to those stubborn dry patches.</p>
                <div class="title">11. Increases firmness in skin</div>
                <p>Use circular motions to work the scrub into your skin to help improve firmness so you can rock that bikini with ease.</p>
            </div>
        </div>
    </div> --}}

    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>

</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/product_2.css">
@endsection

@section("script")
<script type="text/javascript">
/*$(document).ready(function($) {

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
});*/

</script>


@endsection