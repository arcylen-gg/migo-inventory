@extends('member.layout')
@section('content')
<form class="form-to-submit" method="post">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Products / {{ $product->product_name }} / <text id="variant_name"></text> &raquo; Edit</span>
                    <small>
                    Edit product variants
                    </small>
                </h1>
                <button type="submit" class="panel-buttons btn btn-primary pull-right">Save Variant</button>
                <a href="" class="panel-buttons btn btn-danger pull-right {{Request::input('variant_id') ? '' : 'hide'}}" id="delete_variant">Delete Variant</a>
                <a href="/member/product/edit/{{ $product->product_id }}" class="panel-buttons btn btn-default pull-right">Cancel</a>
            </div>
        </div>
    </div>
	@if(session()->has('message'))
    <div class="alert alert-dismissable alert-danger fade in">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
        <span class="title"><i class="icon-remove-sign"></i> ERROR</span>
        {{ session('message') }}
    </div>
    @endif
    <div class="row">
        <div class="col-md-4">
            <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" class="" name="product_id" id="product_id" value="{{ $product->product_id }}">
            <!-- FORM.TITLE -->
            <div class="form-box-divider">
                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ $image->image_path }}" style="height: 70px">
                    </div>
                    <div class="col-md-9">
                        <div class="fieldset">
                            <!-- title -->
                            <label>Product Name</label>
                            <div class="title">{{ $product->product_name }}</div>
                            <div>{{ $_product_variant->count() }} variants</div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- FORM.VARIANTS -->
            <div class="form-box-divider">
                @foreach($_product_variant as $key=>$variant)
                    <div class="variant-item variant-nav-container" id="{{ $variant->variant_id }}">
                        <div class="variant-nav-list">
                            <div class="image-container">
                                <img src="{{ $variant->image_path }}">
                            </div>
                            <div class="text">{{ $variant->option_value_dot }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
    
            
            
        </div>
        <div class="col-md-8">

            <!-- FORM.VARIANTS.DETAILS -->
            <div class="form-box-divider light">
                <input value="" type="text" class="hidden" name="variant_id" id="variant_id">
                <input type="hidden" class="image-main-id" name="image_main_id" value="">
                <div class="title">Options</div>
                <div class="dividers half"></div>
                <div class="row">
                    <div class="col-md-8" id="variant-options-details">
                    </div>
                    <div class=" col-md-4">
                        <a href="javascipt:;" class="change-image">
                            <div class="upload-style-container">
                                <img src="" id="variant_main_image">
                                <span class="fa fa-photo fn-lg"></span>
                                Choose Image
                            </div>
                        </a>
                    </div>
                </div>

                <div class="dividers"></div>

                <div class="clearfix">
                    <div class="title">Pricing</div>
                </div>
                <div class="dividers half"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="fieldset">
                            <!-- price -->
                            <label>Price</label>
                            <div><input value="" placeholder="0.00" type="text" class="global-price form-control" name="variant_price" id="variant_price" required></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fieldset">
                            <!-- compare pricing-->
                            <label>Compare at Price</label>
                            <div><input value="" placeholder="0.00" type="text" class="global-price form-control" name="variant_compare_price" id="variant_compare_price"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <!-- charge tax -->
                        <div class="checkbox visibile-check-box pull-left">
                            <label><input value="checked" type="checkbox" name="variant_charge_taxes" id="variant_charge_taxes" > Charge taxes on this product</label>
                        </div>
                    </div>
                </div>

                <div class="dividers"></div>

                <div class="clearfix">
                    <div class="title">Inventory</div>
                </div>
                <div class="dividers half"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="fieldset">
                            <label>SKU (Stock Keeping Unit)</label>
                            <div><input value="" placeholder="" type="text" class="form-control" name="variant_sku" id="variant_sku" required></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fieldset">
                            <label>Barcode</label>
                            <div><input value="" placeholder="" type="text" class="form-control" name="variant_barcode" id="variant_barcode"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="fieldset">
                            <!-- inventory policy -->
                            <label>Inventory Policy</label>
                            <div>
                                <select name="variant_track_inventory" class="form-control inventory-policy-select" id="variant_track_inventory">
                                    <option value="0">Don't track inventory</option>
                                    <option value="1">I want to track the inventory of this product</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="fieldset">
                            <!-- Quantity-->
                            <label>Quantity</label>
                            <div><input value="" placeholder="" type="text" class="form-control" name="variant_inventory_count" id="variant_inventory_count"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="fieldset">
                            <label>Incoming</label>
                            <div><input value="" placeholder="" type="text" class="form-control" name="" id=""></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Track Inventory -->
                    <div class="col-md-12">
                        <div class="checkbox visibile-check-box pull-left inventory_strict">
                            <label><input value="checked" type="checkbox" name="variant_allow_oos_purchase" id="variant_allow_oos_purchase"> Allow purchase of this product even if there is no more stocks</label>
                        </div>
                    </div>
                </div>

                <div class="dividers"></div>

                <div class="clearfix">
                    <div class="title">Shipping</div>
                </div>
                <div class="dividers half"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="fieldset">
                            <!-- price -->
                            <label>Weight</label>
                            <div class="input-group">
                                <input value="" name="variant_weight" placeholder="0" type="number" step="any" class="form-control" id="variant_weight">
                                <span class="input-group-addon" style="padding:0px;background-color: #ffffff;">
                                    <select name="variant_weight_lbl" style="padding:6px;border:none;" id="variant_weight_lbl">
                                        <option value="kg">kg</option>
                                        <option value="lb">lb</option>
                                        <option value="oz">oz</option>
                                        <option value="g">g</option>
                                        <option value="L">L</option>
                                        <option value="ml">ml</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="fieldset">
                            <div class="checkbox">
                                <label><input value="checked" name="variant_require_shipping" type="checkbox" id="variant_require_shipping"> This product requires shipping</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<div class="hidden">
    <div class="script-variant-details">
        <div class="dividers half"></div>
        <label></label>
        <input name="option_name[]" value="" type="text" class="hidden option-name">
        <input name="option_value[]" value="" placeholder="" type="text" class="form-control option-value" data-id="" required>
    </div>
</div>


<div class="modal fade" id="my-modal-image" role="dialog">
    <form class="save-image-variant" method="post" action="/member/product/edit/variant/image/save_image">
        <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" class="image-variant-id" name="variant_id" value="{{ Request::input('variant_id') }}">
        <input type="hidden" class="image-id" name="image_id" value="">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Choose Image</h4>
                    
                </div>
                <div class="modal-body">
                    <div class="image-modal-container clearfix">
                        @foreach($_image as $image)
                            <div class="col-md-3">
                                <image src="{{$image->image_path}}" class="image-modal-item" id="{{$image->image_id}}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-save btn-save-img">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/product.css">
<link rel="stylesheet" type="text/css" href="/assets/external/selectize.js/dist/css/selectize.default.css">
@endsection
        
@section('script')
<script type="text/javascript">
    var global_variant_id   = "{{ Request::input('variant_id') }}";
    var product_id          = {{ $product->product_id }};
    @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
    @endif
</script>
<script type="text/javascript" src="/assets/member/js/variant.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/product.js"></script> -->
<script type="text/javascript" src="/assets/external/selectize.js/dist/js/standalone/selectize.js"></script>
<script>tinymce.init({ selector:'.tinymce',menubar:false,height:200, content_css : "/assets/member/css/tinymce.css"});</script>
@endsection