var product = new product();

function product()
{
    init();

    function init()
    {
        document_ready();
    }

    function document_ready()
    {
        $(document).ready(function()
        {
            action_enable_filter_price();
        });
    }

    function action_enable_filter_price()
    {
        $("#slider-range").slider(
        {
            range: true,
            min: min_price,
            max: max_price,
            values: [current_min, current_max],
            slide: function(event, ui)
            {
                $("#amount").text("₱ " + ui.values[0] + " - ₱ " + ui.values[1]);
                $("#min-holder").val(ui.values[0]);
                $("#max-holder").val(ui.values[1]);
            }
        });

        $("#amount").text("₱ " + $("#slider-range").slider("values", 0) + " - ₱ " + $("#slider-range").slider("values", 1));
    }
}