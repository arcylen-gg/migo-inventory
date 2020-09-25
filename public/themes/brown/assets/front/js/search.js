var search = new search();
var page = 1;
var account_ajax_loading;

function search()
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
        add_event_pagination_click();
        action_load_table();

    }

    function add_event_pagination_click()
    {
        $("body").on("click", ".pagination a", function(e)
        {
            $link = $(e.currentTarget).attr("href");
            page = gup("page", $link);
            action_load_table();
            return false;
        });
    }
    
    function action_load_table()
    {
    	$(".show-loader").slideDown();

        if(account_ajax_loading)
        {
            table_ajax_loading.abort();
        }
        
        table_ajax_loading = $.ajax(
        {
            url: '/load_search',
            data: {page:page, search:search_value},
            traditional: true,
            type:"get",
            success: function(data)
            {
                $(".show-loader").slideUp();
                $(".load-table").hide().html(data).fadeIn(1500);
                
            },
            error: function(data)
            {
                alert("ERROR!:" + data);
            }
        });
    }

    

    function gup( name, url )
    {
          if (!url) url = location.href;
          name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
          var regexS = "[\\?&]"+name+"=([^&#]*)";
          var regex = new RegExp( regexS );
          var results = regex.exec( url );
          return results == null ? null : results[1];
    }
}
