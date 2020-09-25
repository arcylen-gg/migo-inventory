@extends("layout")
@section("content")
<section class="header">
    <div class="container">
        <div class="title">Cellcare Health Supplement</div>
    </div>
</section>
<section class="main">
    <div class="container">
        <div class="row clearfix">
            <div class="col-md-6">
                <div class="img"><img src="/themes/{{ $shop_theme }}/img/brand.jpg"></div>
            </div>
            <div class="col-md-6">
                <div class="title">Description</div>
                <div class="desc">dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</div>
                <div class="price">P 0.00</div>
                <div class="qty-cart">
                    <input type="number" class="form-control">
                    <button class="btn btn-default">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/product_content.css">
@endsection

@section("script")

@endsection