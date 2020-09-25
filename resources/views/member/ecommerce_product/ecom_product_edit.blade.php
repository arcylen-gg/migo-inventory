@extends('member.layout')
@section('content')
<form class="global-submit" action="/member/ecommerce/product/edit/{{$product->eprod_id}}" method="post">
    <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" class="button-action" name="button_action" value="">
    <input type="hidden" class="token" name="product_id" value="{{ $product->eprod_id  }}">
    <div class="panel panel-default panel-block panel-title-block" id="top">
        <div class="panel-heading">
            <div>
                <i class="fa fa-tags"></i>
                <h1>
                    <span class="page-title">Products / {{ $product->eprod_id }} &raquo; Edit</span>
                    <small>
                   
                    </small>
                </h1>

                <button type="submit" class="panel-buttons btn btn-custom-primary pull-right save-and-edit" method="post">Save</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right save-and-new" method="post">Save and New</button>
                <button type="submit" class="panel-buttons btn btn-custom-white pull-right save-and-close" method="post">Save and Close</button>
                <a href="/member/ecommerce/product/delete-product/{{$product->eprod_id}}" class="panel-buttons btn btn-danger pull-right" id="delete_variant">Delete Product</a>
                <a href="/member/ecommerce/product/list" class="panel-buttons btn btn-default pull-right">Cancel</a>
            </div>
        </div>
    </div>
    <div class="row load-container">
        <div class="data-container">
           <div class="col-md-4">
              <div class="form-box-divider">
                 <div class="title">Product</div>
                 <div class="dividers half"></div>
                 <div class="row clearfix">
                    <div class="form-group col-md-12">
                       <label>Product Name</label>
                       <input type="text" name="eprod_name" class="form-control input-sm" value="{{$product->eprod_name}}">
                    </div>
                    <div class="form-group col-md-12">
                       <label>Category</label>
                       <select class="form-control select-category input-sm" name="eprod_category_id">
                       @include("member.load_ajax_data.load_category", ['add_search' => "", 'type_id' => $product->eprod_category_id])
                       </select>
                    </div>
                 </div>
              </div>
           </div>
           <div class="col-md-8">
              @if($product->eprod_is_single == 1)
              <div class="form-box-divider single-container">
                 @include('member.ecommerce_product.ecom_edit_variant',['default' => '1'])
              </div>
              @else
              <div class="form-box-divider">
                 <!-- FORM.VARIATIONS -->
                 <div class="variation-form multiple-variation">
                    <!-- <div class="dividers"></div> -->
                    <div class="clearfix">
                       <div class="title pull-left"> Variant </div>
                       <a class="pull-right left-margin" href="/member/ecommerce/product/edit-variant/{{ $product->eprod_id }}"><i class="fa fa-plus"></i> Add Variant</a>
                       <a class="pull-right left-margin popup" href="javascript:" link="/member/ecommerce/product/edit-option/{{ $product->eprod_id }}" size="md" ><i class="fa fa-pencil-square-o"></i> Edit Options </a>
                       <!--<a class="pull-right modal-reorder-variant" href="javascript:"><i class="fa fa-bars"></i> Reorder Variants </a>-->
                    </div>
                    <div class="dividers half"></div>
                    <div class="row">
                       <table class="table table-stripped" >
                          <thead>
                             <tr>
                                <th>
                                   <image src=""></image>
                                </th>
                                @foreach($_column as $key=>$column_name)
                                <th>{{ ucfirst($column_name) }}</th>
                                @endforeach
                                <th>Label</th>
                                <th>Price</th>
                                <th>Action</th>
                             </tr>
                          </thead>
                          <tbody>
                             @foreach($_variant as $key=>$variants)
                             <tr>
                                <td>
                                   <image src="{{$variants->image_path}}" class="variant-image-item"></image>
                                </td>
                                @foreach($variants->variation as $key2=>$column)
                                <td>{{$column}}</td>
                                @endforeach
                                <td>{{$variants->evariant_item_label}}</td>
                                <td>{{currency('',$variants->evariant_price)}}</td>
                                <td>
                                   <div class="btn-group"> 
                                      <a class="btn btn-primary grp-btn" href="/member/ecommerce/product/edit-variant/{{ $variants->evariant_prod_id }}?variant_id={{ $variants->evariant_id }}">Edit</a>
                                      <a class="btn btn-primary grp-btn" href="/member/product/edit/variant/delete/{{ $variants->evariant_id  }}"><span class="fa fa-trash"></span></a>
                                   </div>
                                </td>
                             </tr>
                             @endforeach
                          </tbody>
                       </table>
                    </div>
                 </div>
              </div>
              @endif
           </div>
           <div class="col-md-12">
              <div class="form-box-divider">
                 <div class="form-group">
                    <label>Product Details</label>
                    <textarea class="tinymce form-control" name="eprod_details">{{ $product->eprod_details }}</textarea>
                 </div>
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
                        @include("member.load_ajax_data.load_item_category", ['add_search' => ""])
                    </select>
                </td>
                <td>
                    <a href="#" class="product-info popup" link="/member/ecommerce/product/variant-modal/" size="lg"  product-id="" product-code="">
                    <i class="fa fa-cog fa-lg"></i> Product info</a>
                </td>
                <td>
                    <input class="variant_checked" name="variant_checked[]" type="hidden" value="true">
                    <input class="variant_combination" name="variant_combination[]" type="hidden" value="">
                </td>
            </tr>
        </tbody>
    </table>

     <!-- EDIT OPTIONS -->
    <div class="script-option-container">
        <div class="row clearfix option-container form-group" id="">
            <div class="col-md-5">
                <input class="form-control option-name" type="text" name="option_name[]" value="" id="" required>
                <input class="hidden option-name-id" type="text" name="option_name_id[]" value="">
            </div>
            <div class="col-md-7 option-value-container">
                
            </div>
        </div>
    </div>
    <div class="script-option-item-container">
        <div type="text" class="btn btn-primary remove-option-value" >
            <text class="option-value" value="">Option Value Name</text>
            &times;
        </div>
    </div>
    <!-- END OF EDIT OPTIONS -->
</div>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/member/css/product.css">
<link rel="stylesheet" type="text/css" href="/assets/external/selectize.js/dist/css/selectize.default.css">
@endsection

@section('script')
<script type="text/javascript">
    global_variant_id = '';
    @if(Session::has('success'))
        toastr.success('{{Session::get('success')}}');
    @endif
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.5/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({ 
    selector:'.tinymce',
    plugins: "autoresize",
 });
 </script>
<script type="text/javascript" src="/assets/member/js/evariant.js"></script>
<script type="text/javascript" src="/assets/external/selectize.js/dist/js/standalone/selectize.js"></script>
@endsection