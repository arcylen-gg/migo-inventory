@extends("layout")
@section("content")
<div class="content">
    <!-- Media Slider -->
    <div id="home" class="slider-wrapper single-item">
        @if(loop_content_condition($shop_theme_info, "home", "home_slider"))
            @foreach(loop_content_get($shop_theme_info, "home", "home_slider") as $slider)
            <img src="{{ $slider }}">
            @endforeach
        @else
        <img src="/themes/{{ $shop_theme }}/img/home-banner.jpg">
        <img src="/themes/{{ $shop_theme }}/img/home-slide-2.jpg">
        @endif
    </div>

    <!-- About P4ward -->
    <div id="aboutus" class="wrapper-1">
        <div class="container">
            <div class="row clearfix">
                <div class="wow fadeInLeft col-md-8">
                    <!-- History of P4ward -->
                    <div class="title-container">
                        <span class="icon-container"><img src="/themes/{{ $shop_theme }}/img/p4ward-icon-blue.png"></span><span class="title-blue">Our </span><span class="title-orange">History</span>
                    </div>
                    <div class="details-container">
                        {!! get_content($shop_theme_info, "home", "home_history_front") !!}
                    </div>
                    <div class="button-container"><a href="/about">Read More &raquo;</a></div>
                </div>
                <div class="wow fadeInRight col-md-4">
                    <div class="right-container">
                        <!-- Purpose of P4ward -->
                        <div class="title-container">
                            <span class="title-white">Our </span><span class="title-orange">Purpose</span>
                        </div>
                        <p>{{ get_content($shop_theme_info, "home", "home_our_purpose") }}</p>
                        <!-- Mission -->
                        <div class="subtitle-container">
                            <span class="icon"><img src="/themes/{{ $shop_theme }}/img/icon-mission.png"></span><span class="title">Mission</span>
                        </div>
                        <p>{{ get_content($shop_theme_info, "home", "home_mission") }}</p>
                        <!-- Vision -->
                        <div class="subtitle-container">
                            <span class="icon"><img src="/themes/{{ $shop_theme }}/img/icon-vision.png"></span><span class="title">Vision</span>
                        </div>
                        <p>{{ get_content($shop_theme_info, "home", "home_vision") }}</p>
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <!-- Why P4ward -->
    <div class="wrapper-2">
        <div class="container">
            <div class="wow fadeInDown title-container">
                <span class="icon-container"><img src="/themes/{{ $shop_theme }}/img/p4ward-icon-white.png"></span><span class="title-white">Why </span><span class="title-orange">P4ward</span>
            </div>
            <div class="bottom-container">
                <di class="row clearfix">
                    <div class="wow fadeInLeft col-md-5">
                        <div class="image-holder">
                            <img src="{{ get_content($shop_theme_info, "home", "home_why_p4ward_image") }}">
                        </div>
                    </div>
                    <div class="wow fadeInRight col-md-7">
                        <div class="title">Why Join P4ward</div>
                        <div class="details-container">
                            {!! get_content($shop_theme_info, "home", "home_why_p4ward_context") !!}
                        </div>
                    </div>
                </di>
            </div>
        </div>
    </div>

    <!-- Product of P4ward -->
    <div id="product" class="wrapper-3">
        <div class="container">
            <div class="wow fadeInDown title-container">
                <span class="icon-container"><img src="/themes/{{ $shop_theme }}/img/p4ward-icon-blue.png"></span><span class="title-blue">Our </span><span class="title-orange">Products</span>
                <div class="row clearfix">
                    <div class="col-md-6">
                        <div class="wow fadeInLeft product-container" data-wow-delay=".6s">
                            <div class="percent-container">{{ get_content($shop_theme_info, "home", "home_percent_of_product_1") }}</div>
                            <div class="product-title-container">{{ get_content($shop_theme_info, "home", "home_p4ward_product_1") }}</div>
                            <div class="product-image"><img src="{{ get_content($shop_theme_info, "home", "home_image_product_1") }}"></div>
                            <div class="button-container"><a href="/product"><button>See Benefits</button></a></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="wow fadeInRight product-container" data-wow-delay=".6s">
                            <div class="percent-container">{{ get_content($shop_theme_info, "home", "home_percent_of_product_2") }}</div>
                            <div class="product-title-container">{{ get_content($shop_theme_info, "home", "home_p4ward_product_2") }}</div>
                            <div class="product-image"><img src="{{ get_content($shop_theme_info, "home", "home_image_product_2") }}"></div>
                            <div class="button-container"><a href="/product2"><button>See Benefits</button></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits of their Product -->
    <div class="wrapper-4" style="background-image: url('/themes/{{ $shop_theme }}/img/wrapper4-banner.jpg')">
        <div class="row clearfix">
            <div class="col-md-4">
                <div class="wow fadeInLeft benefits-container">
                    <div class="image-holder"><img src="/themes/{{ $shop_theme }}/img/wrapper4-image1.png"></div>
                    <div class="title-container">100 % Ogranic</div>
                    <div class="details-container">Organically produced ingredients. Processed without the use of any chemicals.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="wow fadeInDown benefits-container">
                    <div class="image-holder"><img src="/themes/{{ $shop_theme }}/img/wrapper4-image2.png"></div>
                    <div class="title-container">Rich Source of Antioxidants</div>
                    <div class="details-container">Choosing organic food can lead to increased intake of nutritionally desirable antioxidants.</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="wow fadeInRight benefits-container">
                    <div class="image-holder"><img src="/themes/{{ $shop_theme }}/img/wrapper4-image3.png"></div><div class="title-container">2x More Caffeine</div>
                    <div class="details-container">Don Organics Robusta coffee scrub caffeine content twice as potent as other coffee scrub.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Don Organics -->
    <div class="wrapper-5">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-7">
                    <div class="wow fadeInLeft title-container">What makes Don Organics Different from Other Coffee Scrubs?</div>
                    <div class="wow fadeInLeft details-container">
                        {!! get_content($shop_theme_info, "home", "home_coffee_diff_context") !!}
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="wow fadeInRight image-holder"><img src="{{ get_content($shop_theme_info, "home", "home_coffee_diff_image") }}"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials of P4ward -->
    <div id="testimonials" class="wrapper-6">
        <div class="container">

            <div class="wow fadeInDown title-container">
                <span class="icon-container"><img src="/themes/{{ $shop_theme }}/img/p4ward-icon-white.png"></span><span class="title-white">What </span><span class="title-orange">They </span><span class="title-orange">Say</span>
            </div>

            <div class="says-container">
                <div class="holder wow fadeInDown" data-wow-delay=".2s">
                    <div class="feedback-container match-height">
                        <div class="top-container">
                            <div class="row-no-padding clearfix">
                                <div class="col-md-3">
                                    <div class="left">
                                        <div class="image-holder">
                                            <img src="/themes/{{ $shop_theme }}/img/wrapper6-image1.png">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="right">
                                        <div class="name">Mumai Vitangcol Nidea</div>
                                        <div class="date">January 10</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bottom-container">
                            <div class="description">Another must haves! I rarely use skin essentials but this one's on top of my list now! You can use it on your face and body and see/feel the result right after using it. One of the best products I've used and I just love that freshly brewed coffee smell. Two thumbs up!</div>
                            <div class="star"><img src="/themes/{{ $shop_theme }}/img/wrapper6-star.png"></div>
                        </div>
                    </div>
                </div>
            
                <div class="holder wow fadeInDown" data-wow-delay=".3s">
                    <div class="feedback-container match-height">
                        <div class="top-container">
                            <div class="row-no-padding clearfix">
                                <div class="col-md-3">
                                    <div class="left">
                                        <div class="image-holder">
                                            <img src="/themes/{{ $shop_theme }}/img/wrapper6-image2.png">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="right">
                                        <div class="name">Maricar-Anthony Sierra</div>
                                        <div class="date">February 7</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bottom-container">
                            <div class="description">Wow! I usually don't write reviews, but I read tons of reviews before trying out something, and I just had to add another good one. I have tried hundreds of products and this is probably the best scrub I have ever come across! Cleared up my excema and makes my skin soo soft! Worth the money!! Will Def repurchase when this bag runs out.</div>
                            <div class="star"><img src="/themes/{{ $shop_theme }}/img/wrapper6-star.png"></div>
                        </div>
                    </div>
                </div>
            
                <div class="holder wow fadeInDown" data-wow-delay=".4s">
                    <div class="feedback-container match-height">
                        <div class="top-container">
                            <div class="row-no-padding clearfix">
                                <div class="col-md-3">
                                    <div class="left">
                                        <div class="image-holder">
                                            <img src="/themes/{{ $shop_theme }}/img/wrapper6-image3.png">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="right">
                                        <div class="name">Shiela Mae San Diego</div>
                                        <div class="date">January 8</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bottom-container">
                            <div class="description">Makes my skin soft and smooth after using it. It's a bit messy to use but rinses easily with (a lot of) water.</div>
                            <div class="star"><img src="/themes/{{ $shop_theme }}/img/wrapper6-star.png"></div>
                        </div>
                    </div>
                </div>
            
                <div class="holder wow fadeInDown" data-wow-delay=".5s">
                    <div class="feedback-container match-height">
                        <div class="top-container">
                            <div class="row-no-padding clearfix">
                                <div class="col-md-3">
                                    <div class="left">
                                        <div class="image-holder">
                                            <img src="/themes/{{ $shop_theme }}/img/wrapper6-image4.png">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="right">
                                        <div class="name">Yan Chino Dino Villanueva</div>
                                        <div class="date">January 8</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bottom-container">
                            <div class="description">Amazing as ever...<br>I'm so inlove with this product...</div>
                            <div class="star"><img src="/themes/{{ $shop_theme }}/img/wrapper6-star.png"></div>
                        </div>
                    </div>
                </div>

                <div class="holder wow fadeInDown" data-wow-delay=".6s">
                    <div class="feedback-container match-height">
                        <div class="top-container">
                            <div class="row-no-padding clearfix">
                                <div class="col-md-3">
                                    <div class="left">
                                        <div class="image-holder">
                                            <img src="/themes/{{ $shop_theme }}/img/wrapper6-image5.png">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="right">
                                        <div class="name">Christopher John Tumalad</div>
                                        <div class="date">January 8</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bottom-container">
                            <div class="description">This is what I'm using now.</div>
                            <div class="star"><img src="/themes/{{ $shop_theme }}/img/wrapper6-star.png"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Network Marketing PDF link-->
    <div class="wrapper-x" style="background-image: url('/themes/{{ $shop_theme }}/img/network-marketing-bg.jpg')">
        <div class="container">
            <div class="wrapper-x-title wow fadeInDown" data-wow-delay = ".2s">Network Marketing Without Recruitment</div>

            <div class="btn-container wow fadeInDown" data-wow-delay = ".4s">
                <a href="https://drive.google.com/file/d/0B9TqTDu5OK_3Mm5qdGoyZ2huRFl2ZTd1SS01Q1c3d1EyY1lJ/view" target="_blank"><button>Read More &raquo;</button></a>
            </div>
        </div>
    </div>

    <!-- Contact -->
    <div id="contactus" class="wrapper-7">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-6">
                    <div class="title-container">Get Intouch With Us</div>
                     <div class="row clearfix">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="First Name*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Last Name*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                    <input type="phone" class="form-control" placeholder="Phone Number*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email Address*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text area" class="form-control" placeholder="Subject*">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea type="text" class="form-control text-message" placeholder="Message*"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="button-container">
                                <a href="#Read More">SEND</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <span class="icon"><img src="/themes/{{ $shop_theme }}/img/icon-address.png"></span><span class="title">Business Address</span>
                    <div class="details-container"><p>{{ get_content($shop_theme_info, "home", "home_business_address") }}</p></div>
                    <span class="icon"><img src="/themes/{{ $shop_theme }}/img/icon-envelope.png"></span><span class="title">Email Address</span>
                    <div class="details-container"><p>{{ get_content($shop_theme_info, "home", "home_email_address") }}</p></div>
                    <span class="icon"><img src="/themes/{{ $shop_theme }}/img/icon-mobile.png"></span><span class="title">Contact Number</span>
                    <div class="number-container"><p>Phone: {{ get_content($shop_theme_info, "home", "home_phone_number") }}</p><p>Mobile: {{ get_content($shop_theme_info, "home", "home_mobile_number") }}</p></div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>

</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/home.css">
@endsection

@section("script")

<script type="text/javascript">

    $(document).ready(function()
    {
        $('.single-item').slick
        ({
            prevArrow:"<img class='a-left control-c prev slick-prev' src='/themes/{{ $shop_theme }}/img/arrow-left.png'>",
            nextArrow:"<img class='a-right control-c next slick-next' src='/themes/{{ $shop_theme }}/img/arrow-right.png'>",
            dots: false,
            autoplay: true,
            autoplaySpeed: 3000,
        });

        lightbox.option({
          'disableScrolling': true,
          'wrapAround': true
        });

    });
    
</script>

<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/home.js"></script>

@endsection