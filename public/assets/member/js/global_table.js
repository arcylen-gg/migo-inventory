var global_table = new global_table();
var table_page = 1;

function global_table()
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
        action_load_table();
        event_tab_change();
        add_event_pagination();
    }    
    function add_event_pagination()
    {
        $("body").on("click", ".pagination a", function(e)
        {
            $url = $(e.currentTarget).attr("href");
            var url = new URL($url);
            $page = url.searchParams.get("page");
            table_page = $page;
            action_load_table();
            return false;
        });
    }
    function action_load_table()
    {
        var currentLocation = window.location + "/table?page=" + table_page;

        $active_tab = $(".change-tab.active").attr("mode");

        $(".active-tab").val($active_tab);

        $(".load-table").html('<div style="text-align: center; margin-top: 100px; margin-bottom: 100px; font-size: 25px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>')

        var form_data = $(".table-form-data").serialize();

        console.log(form_data);

        $.ajax(
        {
            url: currentLocation,
            data: form_data,
            type:"post",
            success: function(data)
            {
                $(".load-table").html(data);
            }
        });
    }
    function event_tab_change()
    {
        $(".change-tab").click(function(e)
        {
            $(".change-tab").removeClass("active");
            $(e.currentTarget).addClass("active");
            table_page = 1;
            action_load_table();
        });
    }
    this.action_load_table = function()
    {
        action_load_table();
    }
}
function success_confirm(data)
{
    if(data.status == 'success')
    {
        toastr.success("Success");
        data.element.modal("hide");
        global_table.action_load_table();
    }
}