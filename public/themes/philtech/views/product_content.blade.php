@extends('layout')
@section('content')
<div class="content mob-margin">
    <div class="container">
        <div class="row clearfix">
            <div class="col-md-12">
                <div class="breadcrumbs">
                    <div class="holder"><a href="/">HOME</a></div>
                    <div class="holder">•</div>
                    @foreach($breadcrumbs as $breadcrumb)
                        @if($loop->last)
                            <div class="holder active"><a href="/product?type={{ $breadcrumb["type_id"] }}">{{ $breadcrumb["type_name"] }}</a></div>
                            <div class="holder">•</div>
                        @else
                            <div class="holder"><a href="/product?type={{ $breadcrumb["type_id"] }}">{{ $breadcrumb["type_name"] }}</a></div>
                            <div class="holder">•</div>
                        @endif
                    @endforeach
                    <div class="holder active"><a href="javascript:">{{ $product["eprod_name"] }}</a></div>
                </div>
            </div>
            <!-- ITEM CONTENT -->
            <div class="col-md-9">
                <?php $ctr = 0; ?>
                @foreach($product['variant'] as $key => $product_variant)
                <div class="item-content {{ $ctr != 0 ? 'hide' : '' }}" variant-id="{{ $product_variant['evariant_id'] }}">
                    <div class="featured-container">
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="item-title">{{ $product["eprod_name"] }}</div>
                            </div>
                            <div class="col-md-5 product">
                                <div>
                                    @foreach($product_variant['image'] as $key => $image)
                                    <img id="yellow-bag-image" key="{{ $key }}" variant-id="{{ $product_variant['evariant_id'] }}" class="item-image-large {{ $key == 0 ? '' : 'hide' }}" key="{{ $key }}" style="width: 100%;" src="{{ $image['image_path'] }}" data-zoom-image="{{ $image['image_path'] }}">
                                    @endforeach
                                </div>
                                <div class="thumb">
                                    @foreach($product_variant['image'] as $key => $image)
                                    <div class="holder" style="cursor: pointer;" key="{{ $key }}" variant-id="{{ $product_variant['evariant_id'] }}"><img style="width: 100%; object-fit: cover;" class="item-image-small small-yellow-bag match-height" src="{{ $image['image_path'] }}"></div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-7 item-selected-content">
                                @if(count($product_variant['mlm_discount']) > 0)
                                <div style="margin-top: 15px;">
                                    <table class="table table-bordered table-striped table-hover table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Product Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product_variant['mlm_discount'] as $key => $mlm_discount)
                                            <tr>
                                                <td>{{ $mlm_discount['discount_name'] }}</td>
                                                <td>₱ {{ number_format($mlm_discount['discounted_amount'], 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                                <div class="variant-holder">
                                    @foreach($_variant as $variant)
                                    <div class="holder">
                                        <div class="s-label">Select {{ $variant['option_name'] }}</div>
                                        <div class="select">
                                            <select name="attr[{{ $variant['option_name_id'] }}]" style="text-transform: capitalize;" class="form-control select-variation" variant-label="{{ $variant['option_name'] }}" product-id="{{ $product['eprod_id'] }}" variant-id="{{ $product_variant['evariant_id'] }}">
                                                <option value="0" style="text-transform: capitalize;">{{ $variant['option_name'] }}</option>
                                                @foreach(explode(",", $variant['variant_value']) as $option)
                                                <option value="{{ $option }}" style="text-transform: capitalize;">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="container-quantity-price">
                                    <div class="row clearfix">
                                        <div class="col-md-12">
                                            <div class="item-and-description">
                                                @if($product_variant['item_type_id'] != 2)
                                                <div class="availability">Availability : <span id="stock" style="{{ $product_variant['inventory_status'] == "out of stock" ? "color: red" : ""  }}">{{ ucwords($product_variant['inventory_status']) }}</span></div>
                                                @endif
                                                <!-- <div class="item-description">{!! $product_variant['evariant_description'] !!}</div> -->
                                            </div>
                                        </div>
                                        <div class="col-md-12 price-old-new">
                                            @if($product_variant['discounted'] == "true")
                                            <div class="present-item-price">&#8369; {{ number_format($product_variant['discounted_price'], 2) }}</div>
                                            <div class="item-old-price">&#8369; {{ number_format($product_variant['evariant_price'], 2) }}</div>
                                            @else
                                            <div class="present-item-price">&#8369; {{ number_format($product_variant['evariant_price'], 2) }}</div>
                                            @endif
                                        </div>
                                        <div class="col-md-12 item-quantity">
                                            <div class="quantity-input">
                                                <div class="quantity-label">Quantity</div>
                                                <form>
                                                    <input onChange="$(this).siblings('.product-add-cart').attr('quantity', $(this).val())" style="width: 60px;" class="variation-qty form-control input-lg" type="number" name="quantity" min="1" max="100" value="1">
                                                    <button item-id="{{ $product_variant['evariant_item_id'] }}" quantity="1" class="product-add-cart {{ isset($product['variant'][1]) ? 'disabled' : ($product_variant['item_type_id'] != 2 ? ($product_variant['inventory_status'] == 'out of stock' ? 'disabled' : '') : '') }}" {{ isset($product['variant'][1]) ? 'disabled' : ($product_variant['item_type_id'] != 2 ? ($product_variant['inventory_status'] == 'out of stock' ? 'disabled' : '') : '') }} type="button">Add to Cart</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($product_variant['evariant_description'])
                    <div class="featured-container" style="padding: 25px;">
                        <div class="item-header">Product Details</div>
                        <div class="item-description" style="overflow: auto;">{!! $product_variant['evariant_description'] !!}</div>
                    </div>
                    @endif
                </div>
                <?php $ctr++; ?>
                @endforeach
                <div class="featured-container clearfix">
                    <div class="other-related"><span class="yellow">RELATED</span><span class="blue">PRODUCTS</span></div>
                    <div class="other-related-items row clearfix">
                        @foreach(limit_foreach($_related, 4) as $related)
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="per-item-container">
                                <div class="image-content-1">
                                    <img class="4-3-ratio item-image-large other-related-images" src="{{ get_product_first_image($related) }}">
                                    <button type="button" onClick="location.href='/product/view2/{{ $related['eprod_id'] }}'" class="new-add-to-cart-button btn" >
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i></td>
                                                    <td class="text">Add to cart</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </button>
                                </div>
                                <div class="item-details">
                                    <div class="item-name"><a href="/product/view2/{{ $related['eprod_id'] }}" style="color: #1a1a1a;">{{ get_product_first_name($related) }}</a></div>
                                    <div class="item-price">{{ get_product_first_price($related) }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- SIDE CONTAINER -->
            <div class="col-md-3">
                <div class="hot-deals-container">
                    <div class="wow-title">
                        <span class="orange">HOT</span><span class="blue">DEALS</span>
                        <span class="scroll-button"><a class="left" href="#"><img src="/themes/{{ $shop_theme }}/img/left-button-scroll.png"></a><a class="right" href="#"><img src="/themes/{{ $shop_theme }}/img/right-button-scroll.png"></a></span>
                    </div>
                    <div class="daily-container">
                        @foreach(get_collection(get_content($shop_theme_info, "home", "daily_hot_deals"), $shop_id) as $collection)
                        <div class="holder">
                            <div class="hot-deals-item-container">
                                <img class="4-3-ratio" src="{{ get_collection_first_image($collection) }}">
                                <div class="item-details">
                                    <a href="/product/view2/{{ $collection['product']['eprod_id'] }}">
                                        <div class="item-title">{{ get_collection_first_name($collection) }}</div>
                                    </a>
                                    <div class="item-price">{{ get_collection_first_price($collection) }}</div>
                                </div>
                                <button type="button" onClick="location.href='/product/view2/{{ $collection['product']['eprod_id'] }}'" class="new-add-to-cart-button btn" style="margin-top: 25px;">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="icon"><img src="/themes/{{ $shop_theme }}/img/header/cart-icon.png"></td>
                                                <td class="text">SHOP NOW</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="hot-deals-container" style="margin-top: 30px;">
                    <div class="wow-title">
                        <span class="orange">SPECIAL</span><span class="blue">OFFERS</span>
                    </div>
                    <div class="item-container clearfix">
                        @foreach(get_collection(get_content($shop_theme_info, "home", "special_offers"), $shop_id) as $collection)
                            <div class="row-no-padding clearfix per-item match-height">
                                <div class="col-xs-4"><img style="width: 100%; padding-right: 10px;" class="item-img 4-3-ratio" src="{{ get_collection_first_image($collection) }}"></div>
                                <div class="col-xs-8">
                                    <div class=" item-details-container">
                                        <a href="/product/view2/{{ $collection['product']['eprod_id'] }}"><div class="item-title">{{ $collection['product']['eprod_name'] }}</div></a>
                                        <div class="item-price">{{ get_collection_first_price($collection) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{-- <div class="row-no-padding clearfix per-item">
                            <div class="col-xs-4"><img class="item-img 4-3-ratio" src="/themes/{{ $shop_theme }}/img/product/2.jpg"></div>
                            <div class="col-xs-8">
                                <div class=" item-details-container">
                                    <a href="/product/view2/test"><div class="item-title">Nokia 3310 (20170)</div></a>
                                    <div class="item-price">P 5,990.00</div>
                                </div>
                            </div>
                        </div>
                        <div class="row-no-padding clearfix per-item">
                            <div class="col-xs-4"><img class="item-img 4-3-ratio" src="/themes/{{ $shop_theme }}/img/product/3.jpg"></div>
                            <div class="col-xs-8">
                                <div class=" item-details-container">
                                    <a href="/product/view2/test"><div class="item-title">GSat HD (Complete Set)</div></a>
                                    <div class="item-price">P 2,499.00</div>
                                </div>
                            </div>
                        </div>
                        <div class="row-no-padding clearfix per-item">
                            <div class="col-xs-4"><img class="item-img 4-3-ratio" src="/themes/{{ $shop_theme }}/img/product/4.jpg"></div>
                            <div class="col-xs-8">
                                <div class=" item-details-container">
                                    <a href="/product/view2/test"><div class="item-title">GSat Prepaid (Load Card 500)</div></a>
                                    <div class="item-price">P 500.00</div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("js")
<script type="text/javascript" src="/resources/assets/frontend/js/zoom.js"></script>
<script type="text/javascript" src="/themes/{{ $shop_theme }}/js/product_content.js"></script>
@endsection
@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/product_content.css">
@endsection