@extends('member.layout')
@section('content')
<form class="form-to-submit-add" method="post">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Products &raquo; Add</span>
                    <small>
                    Add a product on your website
                    </small>
                </h1>
                <button type="submit" class="panel-buttons btn btn-primary pull-right">Save Product</button>
                <a href="/member/product/list" class="panel-buttons btn btn-default pull-right">Cancel</a>
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
        <div class="col-md-8">
            <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
            <!-- FORM.TITLE -->
            <div class="form-box-divider">
                <div class="fieldset">
                    <label>Item Reference</label>
                    <div>
                        <select name="item_id" class="form-control">
                            @foreach($_item as $item)
                            <option value="{{ $item->item_id }}">{{ $item->item_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="fieldset">
                    <!-- description -->
                    <label>Description (For Front End)<i class="fa fa-angle-double-down product-description"></i></label>
                    <div class="product-desc-content" style="display:none">
                        <textarea name="product_description" class="form-control input-sm tinymce" >{{ old('product_description') }}</textarea>
                    </div>
                </div>
            </div>
            <!-- FORM.IMAGES -->
            <div class="form-box-divider">
                <div class="clearfix">
                    <div class="title pull-left">Images</div>
                    <a class="image-button pull-right image-gallery" href="javascript:"><i class="fa fa-upload"></i> Upload Image</a>
                    <!--<a class="image-button pull-right" href="#"><i class="fa fa-trash"></i> Delete All Image</a>-->
                    <input id="_file" style="display: none;" type="file" name="image-upload" class="image-uploader" accept="image/*" multiple>
                    <input id="product-main-image" type="hidden" name="product_main_image" value="">
                </div>
                <div class="dividers half"></div>
                <div class="image-upload-container">
                    <!-- FORM.IMAGES.EMPTY -->
                    <div class="upload-empty {{ $_image->isEmpty() ? '' : 'hidden'  }}">
                        <div class="icon"><i class="fa fa-image"></i></div>
                        <div class="word">Drop Files to Upload</div>
                    </div>
                    <!-- FORM.IMAGES.NOT-EMPTY -->
                   <div class="image-container text-left">
                        <span class="old-image-container">
                        </span>
                        <span class="new-image-container"></span>
                   </div> 
                </div>
            </div>

            <div class="form-box-divider">
                <!-- FORM.VARIANT -->
                <div class="variation-form multiple-variation">
                    <div class="dividers"></div>
                    <div class="clearfix">
                        <div class="title">Variant</div>
                    </div>
                    <div class="dividers half"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="fieldset">
                                <div class="checkbox">
                                    <label><input class="variation-check" value="checked" {{ old('variation') == "checked" ? "checked=checked" : "" }} name="variation" type="checkbox"> This product comes in multiple variations like size or color</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 variation-container with-variation">
                            <div class="fieldset">
                                <table class="option-table option-table-list">
                                    <thead>
                                        <tr>
                                            <th width="200px;">Option Name</th>
                                            <th>Option Values</th>
                                            <th width="50px"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr index="1" class="hidden">
                                            <input class="option_enable" type="hidden" name="option_enable[]" value="false">
                                            <td>
                                                <input value="Size" type="text" name="option[]" class="form-control input-sm">
                                            </td>
                                            <td>
                                                <div id="form_tags">
                                                    <input placeholder="Separate options with a comma" type="text" name="option_list[]" class="selectize variant-option">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-default hide-option"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <tr index="2" class="hidden">
                                            <input class="option_enable" type="hidden" name="option_enable[]" value="false">
                                            <td>
                                                <input value="Color" type="text" name="option[]" class="form-control input-sm">
                                            </td>
                                            <td>
                                                <div id="form_tags">
                                                    <input placeholder="Separate options with a comma" type="text" name="option_list[]" class="selectize variant-option">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-default hide-option"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <tr index="3" class="hidden">
                                            <input class="option_enable" type="hidden" name="option_enable[]" value="false">
                                            <td>
                                                <input value="Materials" placeholder="Size" type="text" name="option[]" class="form-control input-sm">
                                            </td>
                                            <td>
                                                <div id="form_tags">
                                                    <input placeholder="Separate options with a comma" type="text" name="option_list[]" class="selectize variant-option">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-default hide-option"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 variation-container  with-variation">
                            <button type="button" class="btn btn-default add-another-option">Add another option</button>
                        </div>

                        <div class="col-md-12 variant-list-container variation-container with-variation hidden">
                            <div class="fieldset">
                                <table class="option-table">
                                    <thead>
                                        <tr>
                                            <th colspan="10">Modify variants to be created:</th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <th>Variant</th>
                                            <th width="100px" class="purchase-information">Cost</th>
                                            <th width="100px" class="sale-information">Price</th>
                                            <th width="120px">SKU</th>
                                            <th width="120px">Barcode</th>
                                            <th class="track_inventory" width="100px">Inventory</th>
                                        </tr>
                                    </thead>
                                    <tbody class="variant-main-container">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- FORM.VISIBILITY -->
            <div class="form-box-divider light">
                <div class="title">Visibility</div>
                <div class="dividers"></div>
                <div class="clearfix">
                    <div class="checkbox visibile-check-box pull-left">
                        <!-- visible -->
                        <label><input value="checked" checked="checked" name="product_visible" type="checkbox"> Online Store</label>
                    </div>

                    <!--<div class="pull-right"><i class="fa fa-calendar"></i></div>-->
                </div>
                <div class="clearfix">
                    <div class="checkbox visibile-check-box pull-left">
                        <!-- visible -->
                        <label><input value="checked" checked="checked" name="product_visible" type="checkbox"> P.O.S Software</label>
                    </div>
                    
                    <!--<div class="pull-right"><i class="fa fa-calendar"></i></div>-->
                </div>
            </div>
    
            <!-- FORM.ORGANIZATIONS -->
            <div class="form-box-divider light">
                <div class="title">Organization</div>
                <div class="dividers half"></div>
                <div class="fieldset">
                    <!-- product type -->
                    <label>Product Type</label>
                    <div>
                        <select name="product_type_list" class="form-control input-sm type-select">
                            @foreach($_type as $type)
                            <option {{ old('product_type_list') == $type->type_id ? "selected=selected" : "" }} value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                            @endforeach
                            <option  {{ old('product_type_list') == "new" ? "selected=selected" : "" }} value="new">+ New Product Type</option>
                        </select>
                        <input style="margin-top: 5px;" name="product_type" value="{{ old('product_type') }}" placeholder="Shirts" type="text" class="form-control input-sm type-textbox">
                    </div>
                </div>
                <div class="fieldset">
                    <!-- vendor -->
                    <label>Vendor</label>
                    <select name="product_vendor_list" class="form-control input-sm vendor-select">
                        @foreach($_vendor as $vendor)
                        <option {{ old('product_vendor_list') == $vendor->vendor_id ? "selected=selected" : "" }} value="{{ $vendor->vendor_id }}">{{ $vendor->vendor_name }}</option>
                        @endforeach
                        <option {{ old('product_vendor_list') == "new" ? "selected=selected" : "" }} value="new">+ New Vendor</option>
                    </select>
                    <div><input style="margin-top: 5px;" name="product_vendor" value="{{ old('product_vendor') }}" placeholder="Nike" type="text" class="form-control input-sm vendor-textbox"></div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="script-hidden-storage hidden">
    <div class="script-image-container">
       <div class="image loading newly-added">
            <input type="hidden" class="image-value" name="product_image[]">
            <div class="loader">
                <div class="progress" style="width: 0"></div>
            </div>
            <div class="black-loader"></div>
            <div class="black-hover">
                <div class="button-icons-container">
                    <div class="icon delete-image">
                        <i class="fa fa-trash"></i>
                        <div class="label">Delete</div>
                    </div>
                    <div class="icon set-default-image"><i class="fa fa-circle-o"></i>
                        <div class="label">Set Default</div>
                    </div>
                </div>
            </div>
            <img src="/assets/member/images/product3.jpg">
       </div>
    </div>
    <table>
        <tbody class="script-variant-container">
            <tr class="new">

                <td><input class="variant_check_click" type="checkbox" checked="checked" value="checked"></td>
                <td><div class="definition"></div></td>
                <td class="purchase-information"><input name="variant_cost[]" type="text" class="variant_cost form-control input-sm"></td>
                <td class="sale-information"><input name="variant_price[]" type="text" class="variant_price form-control input-sm"></td>
                <td><input name="variant_sku[]" type="text" class="variant_sku form-control input-sm"></td>
                <td><input name="variant_barcode[]"  type="text" class="variant_barcode form-control input-sm"></td>
                <td class="track_inventory"><input name="variant_inventory[]"  value="1" type="text" class="variant_inventory form-control input-sm"></td>
                <td>
                    <input class="variant_checked" name="variant_checked[]" type="hidden" value="true">
                    <input class="variant_combination" name="variant_combination[]" type="hidden" value="">
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/product.css">
<link rel="stylesheet" type="text/css" href="/assets/external/selectize.js/dist/css/selectize.default.css">
@endsection

@section('script')
<script type="text/javascript">
    product_id = 0;
</script>
<script type="text/javascript" src="/assets/member/js/product.js"></script>
<script type="text/javascript">
function submit_selected_image_done(data) 
{ 
    $(".upload-empty").addClass("hidden");

    for($ctr = 0; $ctr < data.image_data.length; $ctr++)
    {
        var html_for_image = $(".script-image-container").html();
        $(".new-image-container").append(html_for_image);
        $(".new-image-container").find(".newly-added").attr("index", $ctr);
        $(".new-image-container").find(".newly-added").removeClass("newly-added");
    }

    // var progress = ((e.loaded/e.total) * 100) - 30;
    // var this_image = image_current;
    // $(".new-image-container .image[index=" + this_image + "]").find(".progress").css("width", progress + "%");
    $.each(data.image_data, function(index, val) 
    {
        var this_image = val.image_path;
        $(".new-image-container .image[index=" + index + "] img").attr("src", this_image);
        $(".new-image-container .image[index=" + index + "] .image-value").val(this_image);
        $(".new-image-container .image[index=" + index + "]").find("img").load(function()
        {
            $(".new-image-container .image[index=" + index + "]").find(".progress").css("width", 100 + "%");
            setTimeout(function()
            {
                $(".new-image-container .image[index=" + index + "]").removeClass("loading");
                $(".new-image-container .image[index=" + index + "]").removeAttr("index");
            }, 0);
        });
    });
}
</script>
<script type="text/javascript" src="/assets/external/selectize.js/dist/js/standalone/selectize.js"></script>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>tinymce.init({ selector:'.tinymce',menubar:false,height:200, content_css : "/assets/member/css/tinymce.css"});</script>
@endsection