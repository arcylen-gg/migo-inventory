@extends('member.layout')
@section('content')
<form class="global-submit" action="/member/ecommerce/product/add" method="post">
    <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" class="button-action" name="button_action" value="">
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
                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right save-and-edit" method="post">Save</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right save-and-new" method="post">Save And New</button>
                <a href="/member/ecommerce/product/list" class="panel-buttons btn btn-default pull-right">Cancel</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <!-- FORM.TITLE -->
            <div class="form-box-divider">
                <div class="form-group btn-group" data-toggle="buttons">
                    <label class="btn btn-primary active btn-toggle" id="single">
                        <input type="radio" name="product_variant_type" value="single" checked> Single
                    </label>
                    <label class="btn btn-default btn-toggle" id="multiple">
                      <input type="radio" name="product_variant_type" value="multiple"> Multiple
                    </label>          
                </div>
                <div class="row clearfix">
                    <div class="form-group col-md-12">
                        <label>Product Name</label>
                        <input type="text" name="eprod_name" class="form-control input-sm" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Category</label>
                        <select class="form-control select-category input-sm" name="eprod_category_id" required>
                            @include("member.load_ajax_data.load_category", ['add_search' => ""])
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-box-divider single-container">
                <div class="clearfix">
                    <div class="title">Product</div>
                </div>
                <div class="dividers half"></div>
                <div class="row product-container">
                    <div class="col-md-5">
                        <label>Item</label>
                        <div class="fieldset">
                            <div>
                                <input name="variant_checked[]" type="hidden" value="true">
                                <input class="hidden item-code" name="item_code[]" />
                                <select class="select-item droplist-item" name="evariant_item_id[]" required>
                                    @include("member.load_ajax_data.load_item", ['add_search' => ""])
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Product Price</label>
                        <div class="fieldset">
                            <input type="text" name="evariant_price[]" class="form-control input-sm" placeholder="Price" required> 
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>Qty on Hand</label>
                        <div class="fieldset">
                            <input type="text" name="item_qty_refill[]" class="form-control input-sm" placeholder="Quantity" value="0">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>More Info</label>
                        <div class="fieldset">
                            <div class="btn-group">
                                <a href="#" class="btn btn-primary btn-grp-primary product-info popup input-sm" link="/member/ecommerce/product/variant-modal/" size="lg" product-id="" product-code=""> Info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-box-divider multiple-container" style="display:none">
                <!-- FORM.VARIANTS -->
                <div class="variation-form multiple-variation">
                    <div class="clearfix">
                        <div class="title">Product</div>
                    </div>
                    <div class="dividers half"></div>
                    <div class="row">
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
                                    <tbody class="body-option-container">
                                        <tr>
                                            <td>
                                                <input value="" type="text" name="option_name[]" class="form-control input-sm">
                                            </td>
                                            <td>
                                                <div id="form_tags">
                                                    <input placeholder="Separate options with a comma" type="text" name="option_value[]" class="selectize variant-option">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-default remove-option"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 variation-container  with-variation">
                            <button type="button" class="btn btn-default add-another-option">Add another option</button>
                        </div>

                        <div class="col-md-12 variant-list-container variation-container with-variation">
                            <div class="fieldset">
                                <table class="option-table">
                                    <thead>
                                        <tr>
                                            <th colspan="10">Modify variants to be created:</th>
                                        </tr>
                                        <tr>
                                            <td width="5%"></td>
                                            <th width="20%">Variant</th>
                                            <th width="40%">Item</th>
                                            <th width="15%">Product Price</th>
                                            <th width="15%">Qty on Hand</th>
                                            <th width="5%"></th>
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
        <div class="col-md-12">
            <div class="form-box-divider">
                <div class="form-group">
                    <label>Product Details</label>
                    <textarea class="mce form-control" name="eprod_details"></textarea>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="script-hidden-storage hidden">
    <table>
        <tbody class="script-tr-container">
            <tr>
                <input class="option_enable" type="hidden" name="option_enable[]" value="true">
                <td>
                    <input value="" type="text" name="option_name[]" class="form-control input-sm" placeholder="option name...">
                </td>
                <td>
                    <div id="form_tags">
                        <input placeholder="Separate options with a comma" type="text" name="option_list[]" class="selectize variant-option">
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-default remove-option"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
    <table>
        <tbody class="script-variant-container">
            <tr class="new product-container">
                <td><input class="variant_check_click" type="checkbox" checked="checked" value="checked"></td>
                <td><div class="definition"></div></td>
                <td>
                    <input class="hidden item-code" name="item_code[]" />
                    <select class="select-item" name="evariant_item_id[]">
                        @include("member.load_ajax_data.load_item", ['add_search' => ""])
                    </select>
                </td>
                <td>
                    <input type="text" name="evariant_price[]" class="form-control input-sm" placeholder="Price">
                </td>
                <td>
                    <input type="text" name="item_qty_refill[]" class="form-control input-sm" placeholder="Quantity" value="0">
                </td>
                <td>
                    <div class="btn-group">
                        <a href="#" class="btn btn-primary btn-grp-primary product-info popup input-sm" link="/member/ecommerce/product/variant-modal/" size="lg" product-id="" product-code=""> Info</a>
                    </div>
                </td>
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
    @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
    @endif
</script>
<script type="text/javascript" src="/assets/member/js/eproduct.js"></script>
<script type="text/javascript" src="/assets/external/selectize.js/dist/js/standalone/selectize.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.5/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({ 
    selector:'.mce',
    plugins: "autoresize",
 });
 </script>
@endsection