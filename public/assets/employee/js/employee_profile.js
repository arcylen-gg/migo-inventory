var employee = new employee();

function employee()
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
        event_click_tab();
    }

    function event_click_tab()
    {
        $('.employee-profile a').click(function(e) 
        {
            e.preventDefault()
            $(this).tab('show')
        });
    }
}
