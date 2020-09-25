var global_cart = new global_cart();

function global_cart()
{
    init();

    function init()
    {
        $(document).ready(function()
        {
            document_ready();
        });
    }
    function document_ready()
    {
        action_quantity_cart();
        event_add_to_cart();
    }
    function event_add_to_cart()
    {
        $("body").on("click", ".product-add-cart", function(e)
        {
            action_add_to_cart(e.currentTarget);
        });
    }
    function action_add_to_cart(self)
    {
        $(self).prop("disabled", true).attr("disabled", "disabled").css("opacity", "0.5");

        var item_id = $(self).attr("item-id");
        var quantity = $(self).attr("quantity");

        $.ajax({
            url: '/cartv2/add',
            type: 'GET',
            dataType: 'json',
            data: 
            {
                item_id: item_id,
                quantity: quantity
            },
        })
        .done(function(data) 
        {
            if (data == "success") 
            {
                action_load_link_to_modal("/cartv2", "lg");
                $(self).removeProp("disabled").removeAttr("disabled").css("opacity", "1");
            }
            else
            {
                alert("Some error occurred. Please contact the administator.");
            }
        })
        .fail(function() 
        {
            console.log("error");
        })
        .always(function() 
        {
            console.log("complete");
        });
    }
    function action_quantity_cart()
    {
        $.ajax({
            url: '/cartv2/quantity',
            type: 'GET',
            dataType: 'json'
        })
        .done(function(quantity) 
        {
            if (quantity > 99) 
            {
                $(".quantity-item-holder").text("99+");
            }
            else
            {
                $(".quantity-item-holder").text(quantity);
            }
        })
        .fail(function() 
        {
            console.log("error");
        })
        .always(function() 
        {
            console.log("complete");
        });
    }
}