<!-- @products $product -->
<!-- @variant $variant -->
<!-- @item $item -->
<!-- @column $column -->

<!-- FORM.VARIANTS.DETAILS -->
<div class="variant-information">
    <input value="{{$variant->evariant_id or ''}}" type="text" class="hidden" name="variant_id" id="variant_id">
    <div class="title">Information</div>
    <div class="dividers half"></div>
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="{{$default == 1 ? 'active' : ''}} cursor-pointer tab" data-id="1"><a class="cursor-pointer" data-toggle="tab" href="#description"><i class="fa fa-tag"></i> Name & Description</a></li>
            @if($product->eprod_is_single == 0)
                <li class="{{$default == 2 ? 'active' : ''}} cursor-pointer tab" data-id="2"><a class="cursor-pointer" data-toggle="tab" href="#options"><i class="fa fa-certificate"></i> Options</a></li>
            @endif
            <li class="{{$default == 3 ? 'active' : ''}} cursor-pointer tab" data-id="3"><a class="cursor-pointer" data-toggle="tab" href="#price"><i class="fa fa-info"></i> Price</a></li>
            <li class="{{$default == 4 ? 'active' : ''}} cursor-pointer tab" data-id="4"><a class="cursor-pointer" data-toggle="tab" href="#image"><i class="fa fa-picture-o"></i> Images</a></li>
            <li class="{{$default == 5 ? 'active' : ''}} cursor-pointer tab" data-id="5"><a class="cursor-pointer" data-toggle="tab" href="#detail"><i class="fa fa-info-circle" aria-hidden="true"></i> Detail</a></li>
        </ul> 
    </div>

    <div class="dividers" style="margin-top: 0"></div>

    <div class="tab-content">
        <div class="tab-pane fade in {{$default == 1 ? 'active' : ''}}" id="description">
            <div class="clearfix">
                <!-- FORM.TITLE -->
                <div class="">
                    <div class="fieldset">
                        <label>Product Item</label>
                        <div class="row">
                            <div class="col-md-6">
                            <select class="select-item droplist-item" name="evariant_item_id">
                                @include("member.load_ajax_data.load_item", ['add_search' => "", 'item_id' => isset($variant) ? $variant->evariant_item_id : ''])
                            </select>
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-custom-white btn-sm popup edit-item" link="" size="lg"> Edit Item</a>
                            </div>
                            <div class="col-md-4">
                                <input class="form-control input-sm" type="text" value="Remaining : {{ $variant->inventory_count or '' }} ({{$variant->inventory_status or ''}})" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="fieldset">
                        <label>Product Label</label>
                        <div>
                            <input class="form-control input-sm" name="evariant_item_label" placeholder="Product Label" value="{{$variant->evariant_item_label or ''}}">
                        </div>
                    </div>

                    <div class="fieldset">
                        <!-- description -->
                        <label>Description</label>
                        <div class="product-desc-content">
                            <textarea name="evariant_description" class="form-control input-sm tinymce" >{{ $variant->evariant_description or '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($product->eprod_is_single == 0)
            <div class="tab-pane fade in {{$default == 2 ? 'active' : ''}}" id="options">
                <div class="clearfix">
                    <!-- FORM.TITLE -->
                    @if(isset($_column))
                        @foreach($_column as $key => $column)
                            <div class="">
                                <label>{{$column}}</label>
                                <input name="option_name[]" value="{{$column}}" type="hidden" class="option-name">
                                <input name="option_value[]" value="{{ $variant->variation[$key] or ''}}" placeholder="" type="text" class="form-control option-value" data-id="">
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif

        <div class="tab-pane fade in {{$default == 3 ? 'active' : ''}}" id="price">
            <div class="clearfix">
                <!-- FORM.TITLE -->
                <div class="row">
                    <div class="col-md-6 fieldset">
                        <label>Product Price</label>
                        <div>
                            <input class="form-control input-sm" name="evariant_price" placeholder="Product Price" value="{{$variant->evariant_price or ''}}">
                        </div>
                    </div>
                    <div class="col-md-6 fieldset">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade in {{$default == 4 ? 'active' : ''}}" id="image">
            <div class="clearfix">
                <!-- FORM.IMAGES -->
                <div class="form-box-divider">
                    <div class="clearfix">
                        <div class="title pull-left">Images</div>
                        <a class="image-button pull-right image-gallery" href="javascript:" key="product-variant"><i class="fa fa-hand-o-up"></i> Select Image</a>
                        <input id="product-main-image" type="hidden" name="product_main_image" value="">
                    </div>
                    <div class="dividers half"></div>
                    <div class="image-upload-container">
                        <!-- FORM.IMAGES.EMPTY -->
                        @if(isset($variant->image))
                            @if($variant->image->count() <= 0)
                                <div class="upload-empty ">
                                    <div class="icon"><i class="fa fa-image"></i></div>
                                    <div class="word"></div>
                                </div>  
                            @endif
                        @endif
                        <!-- FORM.IMAGES.NOT-EMPTY -->
                       <div class="image-container text-left">
                            <span class="old-image-container">
                            </span>
                            <span class="new-image-container">
                            @if(isset($variant->image))
                                @if($variant->image->count() > 0)
                                    @foreach($variant->image as $key => $image)
                                        <div class="image" imgid="{{$image->image_id}}">
                                            <input type="hidden" class="image-id" name="image_id[]" value="{{$image->image_id}}">
                                            <input type="hidden" class="image-value" name="product_image[]" value="{{$image->image_path}}">
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
                                                    @if($image->default_image == 1)
                                                    <div class="icon set-default-image">
                                                        <i class="fa fa-circle"></i>
                                                        <div class="label">Your Default</div>
                                                    </div>
                                                    @else
                                                    <div class="icon set-default-image">
                                                        <i class="fa fa-circle-o"></i>
                                                        <div class="label">Set Default</div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <img src="{{$image->image_path}}">
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                            </span>
                       </div> 
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade in {{$default == 5 ? 'active' : ''}}" id="detail">
            <div class="clearfix row">
                <div class="form-group col-md-6 col-md-offset-3">
                    <label>Product Detail (Image)</label>
                    <input class="product-detail-value" type="hidden" value="{{$product->eprod_detail_image or ''}}" name="eprod_detail_image">
                    <div class="product-detail-image" style="padding-bottom: 75%; height: 0; background-color: #ddd; background-position: center; background-size: contain; background-repeat: no-repeat; {{ $product->eprod_detail_image ? 'background-image: url("' . $product->eprod_detail_image . '")' : '' }}"></div>
                    <div style="margin-top: 15px;">
                        <button style="width: 100%;" class="image-gallery image-gallery-single btn btn-primary" key="product-detail"> Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="script-hidden-storage hidden">
    <div class="script-image-container">
       <div class="image loading newly-added">
            <input type="hidden" class="image-id" name="image_id[]" disabled>
            <input type="hidden" class="image-value" name="product_image[]" disabled>
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
            <img src="">
       </div>
    </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.5/tinymce.min.js"></script>
<script type="text/javascript" src="/assets/member/js/evariant_info.js"></script>