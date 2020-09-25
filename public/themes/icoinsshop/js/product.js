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
            min: 0,
            max: 500,
            values: [75, 300],
            slide: function(event, ui)
            {
                $("#amount").text("$" + ui.values[0] + " - $" + ui.values[1]);
            }
        });
        $("#amount").text("$" + $("#slider-range").slider("values", 0) +
            " - $" + $("#slider-range").slider("values", 1));
    }
}