var globalv2 = new globalv2();
var jqxhr = {abort: function () {}};

var cart_load = ".cart-load";
var input_qty = ".input-quantity";
var rawprice = ".raw-price";
var subtotal = ".sub-total";
var total = ".total";
var button_checkout = ".button-checkout";
var remove_product = ".remove-item-from-cart";
var product_cart_container = ".per-item-container";
var cart_quantity = ".cart-quantity";
var get_cart_quantity = ".quantity-cart-holder";

function globalv2()
{
    this.call_event_cart = function() 
    {
        cart_event();
    }

    init();

    function init()
    {
        action_replace_brokenimg();
        
        $(document).ready(function()
        {
            document_ready();
        });

        $(window).load(function() 
        {
            window_load();
        });
    }
    function document_ready()
    {
        load_cart();
        action_image_crop();
    }
    function window_load()
    {
        if (typeof action_after_load == 'function') 
        {
            action_after_load();    
        }
    }
}

function number_format(number)
{
    var yourNumber = (number).toFixed(2);
    //Seperates the components of the number
    var n= yourNumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return (n.join("."));
}

function image_crop(selector, width, height)
{
    $(selector).css("object-fit", "cover");
    $(selector).keepRatio({ ratio: width/height, calculate: 'height' });
}

function action_image_crop()
{
    image_crop(".1-1-ratio", 1, 1);
    image_crop(".4-3-ratio", 4, 3);
}

function load_cart(show)
{
    $(cart_load).load("/cart", function()
    {
        event_cart_add_qty();
        event_cart_remove_product();
        action_image_crop();
        action_update_quantity_cart();

        if (show == 1) 
        {
            $('.cart-modal').modal();
        }
    }); 
}

function action_update_quantity_cart()
{
    $(cart_quantity).html($(get_cart_quantity).val());
}

function action_disable_checkout_button()
{
    $(button_checkout).attr("disabled", "disabled");
    $(button_checkout).prop("disabled", true);
    $(button_checkout).addClass("disabled");
}

function action_enable_checkout_button()
{
    $(button_checkout).removeAttr("disabled");
    $(button_checkout).removeProp("disabled");
    $(button_checkout).removeClass("disabled");
}

function event_cart_add_qty()
{
    $(cart_load + " " + input_qty).unbind('change');
    $(cart_load + " " + input_qty).bind('change', function(e)
    {
        action_cart_add_qty(e.currentTarget);
    });
}

function action_cart_add_qty(x)
{
    var variation_id = $(x).attr("vid");
    var product_raw_price = parseFloat($(cart_load + " " + rawprice + '[vid="'+variation_id+'"]').text().replace(',',''));
    var product_quantity = parseInt($(x).val());
    
    var product_subtotal = number_format(product_raw_price * product_quantity);

    $(subtotal + '[vid="'+variation_id+'"]').html(product_subtotal);

    action_cart_total_price();
    action_disable_checkout_button();

    jqxhr.abort();

    jqxhr = $.ajax({
        url: '/cart/update',
        type: 'GET',
        dataType: 'json',
        data: {
            variation_id: variation_id,
            quantity: product_quantity
        },
    })
    .done(function() {
        action_enable_checkout_button();
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}

function action_cart_total_price()
{
    var product_total = 0;

    $(cart_load + " " + subtotal).each(function(index, el) 
    {
        product_total += parseFloat($(el).text().replace(',',''));
    });

    $(cart_load + " " + total).html(number_format(product_total));
}

function event_cart_remove_product()
{
    $(cart_load + " " + remove_product).unbind("click");
    $(cart_load + " " + remove_product).bind("click", function(e)
    {
        action_cart_remove_product(e.currentTarget);
    });
}

function action_cart_remove_product(x)
{
    var variation_id = $(x).attr("vid");
    action_disable_checkout_button();
    jqxhr.abort();

    jqxhr = $.ajax({
        url: '/cart/remove',
        type: 'GET',
        dataType: 'json',
        data: {
            variation_id: variation_id
        },
    })
    .done(function() {
        $(cart_load + " " + product_cart_container).remove();
        action_cart_total_price();
        action_enable_checkout_button();
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}

function action_replace_brokenimg()
{
    $("img").error(function () 
    {
      $(this).unbind("error").attr("src", "/assets/front/img/placeholder.png");
    });
}