@extends("layout")
@section("content")

<div class="content">
    
    <section class="header" style="background-image: url('{{ get_content($shop_theme_info, "products", "products_banner") }}')">
        <div class="container">
            <div class="title">Our <span>Products</span></div>
            <div class="sub">{!!get_content($shop_theme_info, "products", "products_banner_context") !!}</div>
        </div>
    </section>
    <section class="main">
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-3 col-sm-6">
                    <div class="holder">
                        <div class="img"><img src="/themes/{{ $shop_theme }}/img/brand.jpg"></div>
                        <div class="name match-height">Cellcare Health Supplement</div>
                        <div class="button-holder">
                            <button type="button" onClick="location.href='/product/view/test'" class="btn btn-primary">Shop Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="holder">
                        <div class="img"><img src="/themes/{{ $shop_theme }}/img/brand.jpg"></div>
                        <div class="name match-height">Cellcare Health Supplement</div>
                        <div class="button-holder">
                            <button type="button" onClick="location.href='/product/view/test'" class="btn btn-primary">Shop Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="holder">
                        <div class="img"><img src="/themes/{{ $shop_theme }}/img/brand.jpg"></div>
                        <div class="name match-height">Cellcare Health Supplement</div>
                        <div class="button-holder">
                            <button type="button" onClick="location.href='/product/view/test'" class="btn btn-primary">Shop Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="holder">
                        <div class="img"><img src="/themes/{{ $shop_theme }}/img/brand.jpg"></div>
                        <div class="name match-height">Cellcare Health Supplement</div>
                        <div class="button-holder">
                            <button type="button" onClick="location.href='/product/view/test'" class="btn btn-primary">Shop Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="holder">
                        <div class="img"><img src="/themes/{{ $shop_theme }}/img/brand.jpg"></div>
                        <div class="name match-height">Cellcare Health Supplement</div>
                        <div class="button-holder">
                            <button type="button" onClick="location.href='/product/view/test'" class="btn btn-primary">Shop Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="holder">
                        <div class="img"><img src="/themes/{{ $shop_theme }}/img/brand.jpg"></div>
                        <div class="name match-height">Cellcare Health Supplement</div>
                        <div class="button-holder">
                            <button type="button" onClick="location.href='/product/view/test'" class="btn btn-primary">Shop Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="holder">
                        <div class="img"><img src="/themes/{{ $shop_theme }}/img/brand.jpg"></div>
                        <div class="name match-height">Cellcare Health Supplement</div>
                        <div class="button-holder">
                            <button type="button" onClick="location.href='/product/view/test'" class="btn btn-primary">Shop Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="holder">
                        <div class="img"><img src="/themes/{{ $shop_theme }}/img/brand.jpg"></div>
                        <div class="name match-height">Cellcare Health Supplement</div>
                        <div class="button-holder">
                            <button type="button" onClick="location.href='/product/view/test'" class="btn btn-primary">Shop Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SCROLL TO TOP -->
    <div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>

@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/product.css">
@endsection

@section("script")

@endsection