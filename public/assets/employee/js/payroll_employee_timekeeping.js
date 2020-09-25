var timekeeping = new timekeeping();

function timekeeping()
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
        event_click_payslip();
    }

    function event_click_payslip()
    {
        /*$('.employee-profile a').click(function(e) 
        {
            e.preventDefault()
            $(this).tab('show')
        });*/
    }
}
