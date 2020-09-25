<form class="global-submit" action="/member/ecommerce/product/save-product-info" method="post">
    <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" class="" name="product_code" value="{{ $product_code }}">
    <input type="hidden" class="" name="item_id" value="{{ $item_info->item_id }}">
    <div class="modal-header" style="padding-bottom: 0px;">
    	<button type="button" class="close" data-dismiss="modal">×</button>
    	<h4 class="modal-title">{{strtoupper($item_info->item_name)}}</h4>
        <ul class="nav nav-tabs">
            <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#description"><i class="fa fa-tag"></i> Name & Description</a></li>
            <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#image"><i class="fa fa-picture-o"></i> Images</a></li>
        </ul>
    </div>


    <div class="tab-content">
        <div class="tab-pane fade in active" id="description">
            <div class="modal-body clearfix">
                <!-- FORM.TITLE -->
                <div class="form-box-divider">
                    <div class="fieldset">
                        <label>Product Label</label>
                        <div>
                            <input class="form-control input-sm" name="evariant_item_label" placeholder="Product Label" value="{{$product_data['product_label'] or $item_info->item_name}}">
                        </div>
                    </div>

                    <div class="fieldset">
                        <!-- description -->
                        <label>Description<i class="fa fa-angle-double-down product-description"></i></label>
                        <div class="product-desc-content">
                            <textarea name="evariant_description" class="form-control input-sm tinymce" >{{ $product_data['product_description'] or '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade in" id="image">
            <div class="modal-body clearfix">
                <!-- FORM.IMAGES -->
                <div class="form-box-divider">
                    <div class="clearfix">
                        <div class="title pull-left">Images</div>
                        <a class="image-button pull-right image-gallery" href="javascript:"><i class="fa fa-hand-o-up"></i> Select Image</a>
                        <input id="product-main-image" type="hidden" name="product_main_image" value="">
                    </div>
                    <div class="dividers half"></div>
                    <div class="image-upload-container">
                        <!-- FORM.IMAGES.EMPTY -->
                        @if(!$product_data["product_image"])
                            <div class="upload-empty ">
                                <div class="icon"><i class="fa fa-image"></i></div>
                                <div class="word"></div>
                            </div>  
                        @endif
                        <!-- FORM.IMAGES.NOT-EMPTY -->
                       <div class="image-container text-left">
                            <span class="old-image-container">
                            </span>
                            <span class="new-image-container">
                                @if($product_data["product_image"])
                                    @foreach($product_data["product_image"] as $key=>$image_id)
                                        <div class="image">
                                            <input type="hidden" class="image-id" name="image_id[]" value="{{$image_id}}">
                                            <input type="hidden" class="image-value" name="product_image[]" value="{{$product_data['product_image_path'][$key]}}">
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
                                            <img src="{{$product_data['product_image_path'][$key]}}">
                                        </div>
                                    @endforeach
                                @endif
                            </span>
                       </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    	<button class="btn btn-primary btn-custom-primary" type="submit">Save</button>
    </div>
</form>

<div class="script-hidden-storage hidden">
    <div class="script-image-container">
       <div class="image loading newly-added">
            <input type="hidden" class="image-id" name="image_id[]">
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
            <img src="">
       </div>
    </div>
</div>

<script src="/assets/external/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="/assets/member/js/evariant_info.js"></script>
