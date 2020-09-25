<form class="global-submit" role="form" action="/member/ecommerce/coupon/generate-code" method="POST" >
    <input type="hidden" name="_token" value="{{csrf_token()}}" >
    <input type="hidden" name="coupon_id" value="{{$coupon->coupon_code_id or ''}}" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title layout-modallarge-title item_title">Generate Coupon Code</h4>
    </div>

    <div class="modal-body modallarge-body-layout background-white form-horizontal menu_container">
        <div class="panel-body form-horizontal">
            <div class="row clearfix">
                <div class="col-md-12">
                    <!-- START CONTENT -->
                    @if(isset($coupon))
                        <div class="form-group">
                            <div class="col-md-12">
                               <label>Coupon Code</label>
                               <input type="text" class="form-control input-sm" name="coupon_amount" value="{{$coupon->coupon_code}}" disabled>                    
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="col-md-4">
                           <label>Minimum Quantity</label>
                           <input class="form-control int-format" type="text" name="coupon_minimum_quantity" value="{{$coupon->coupon_minimum_quantity or ''}}">         
                        </div>
                        <div class="col-md-4">
                           <label>Coupon Amount</label>
                           <input type="text" class="form-control input-sm" name="coupon_amount" value="{{$coupon->coupon_code_amount or ''}}">                    
                        </div>
                        <div class="col-md-4">
                            <label>Coupon Type</label>
                            <select class="form-control input-sm" name="coupon_amount_type" value="">
                                <option value="fixed" {{isset($coupon) ? $coupon->coupon_discounted == 'fixed' ? 'selected' : '' : ''}}>Fixed</option>
                                <option value="percentage" {{isset($coupon) ? $coupon->coupon_discounted == 'percentage' ? 'selected' : '' : ''}}>Percentage</option>   
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                           <label>Product</label>
                        </div>
                    </div>
                    <div class="div-product-list">
                        <div class="form-group">
                            <div class="col-md-10">
                                <div class="checkbox">                                
                                    <label><input type="checkbox" class="all-product-checkbox" name="all_product_id"> Coupon for all products.</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10">
                               <select name="coupon_product_id[]" class="select-product">
                                    @include("member.load_ajax_data.load_product_category", ['add_search' => ""])
                               </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary add-product-btn"><i class="fa fa-plus"></i></button>
                            </div>                          
                        </div>
                        @if(isset($_coupon_product))
                            @if(count($_coupon_product) > 0)
                                @foreach($_coupon_product as $coupon_product)
                                    <div class="form-group">
                                        <div class="col-md-10">
                                            <select name="coupon_product_id[]" class="select-product">
                                                @include("member.load_ajax_data.load_product_category", ['add_search' => "",   'variant_id' => isset($coupon_product) ? $coupon_product->coupon_code_product_id : ''])  
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger" onclick="click_remove($(this))"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    </div>
                    <!-- END CONTENT -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer" >
        <button type="button" class="btn btn-custom-white" data-dismiss="modal">Cancel</button>
        <div class="col-md-4 pull-right">
            <div class="input-group">
                <input type="hidden" class="form-control int-format text-center" name="generate_count" value="1">
                <span class="input-group-btn">
                    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Generate</button>
                </span>
            </div>
        </div>
        <!-- <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Generate</button> -->
    </div>
</form>
<div class="div-script-product hide">
    <div class="form-group">
        <div class="col-md-10 div-col">
            <select name="coupon_product_id[]" class="product-select">
                @include("member.load_ajax_data.load_product_category", ['add_search' => ""])
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger" onclick="click_remove($(this))"><i class="fa fa-times"></i></button>
        </div>
    </div>
</div>
<script type="text/javascript">
var generate_coupon = new generate_coupon();
var global_html = $(".div-script-product").html();
    function generate_coupon()
    {
        init();

        function init()
        {
            action_initialize_select();
            action_click_product();
            action_click_check_all_product();
            click_remove_select();
        }

        function action_initialize_select()
        {
            console.log("hello");
            $(".select-product").globalDropList(
            {
                hasPopup: "false",
                width : "100%",
                placeholder : "Select a product",
            });
            $(".div-product-list div.div-col:last .product-select").globalDropList(
            {
                hasPopup: "false",
                width : "100%",
                placeholder : "Select a product",
            });
        }
        function action_click_product()
        {
            $(".add-product-btn").unbind("click");
            $(".add-product-btn").bind("click", function()
            {
                append_product_row();
            });
        }
        function append_product_row()
        {
            $(".div-product-list").append(global_html);
            action_initialize_select();
        }
        function action_click_check_all_product()
        {
           $(".all-product-checkbox").unbind("click");
           $(".all-product-checkbox").bind("click", function()
           {
               check_if_checked();
           });
        }
        function check_if_checked()
        {
             if($(".all-product-checkbox").is(":checked"))
            {
                $(".select-product").globalDropList("disabled");
                $(".add-product-btn").attr("disabled","disabled");
                $(".product-select").globalDropList("disabled");
            }
            else
            {
                $(".select-product").globalDropList("enabled");
                $(".add-product-btn").removeAttr("disabled");
                $(".product-select").globalDropList("enabled");
            }
        }
        function click_remove_select()
        {
            $(".delete-product-btn").unbind("click");
            $(".delete-product-btn").bind("click", function()
            {
                $(this).parent().parent().remove();
            });
        }
    }
    function click_remove(btn)
    {
        btn.parent().parent().remove();
    }
</script>