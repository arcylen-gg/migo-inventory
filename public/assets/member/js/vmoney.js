var vmoney = new vmoney();

function vmoney()
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
             event_convenience_fee();
             action_convenience_fee();
        });
    }
    
    function event_convenience_fee()
    {
        $('.current-wallet').keyup(action_convenience_fee);
    }
    
    function action_convenience_fee()
    {
        var current_wallet = parseInt($('.current-wallet').val());
        var fixed = parseInt($('.convenience-fee').attr("fixed"));
        var percent = parseInt($('.convenience-fee').attr("percent"));
        var percent_value = (percent / 100) * current_wallet;
        var convenience_fee = fixed + percent_value; 
        var total_fee = current_wallet + convenience_fee;
        $('.convenience-fee').val(fixed);
        $('.tax-fee').val(percent_value);
        $('.total-fee').val(total_fee);
    }
}