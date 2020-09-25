var product_filter = new product_filter();
var timer_search;
//var filter = "active";
var page = 1;
var account_ajax_loading;
var sorted_by   = null;
var category    = null;

function product_filter()
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
        add_event_change_sorted()
        add_event_click_category()
        // action_load_table();
        add_search_event();
    }
    
    function add_search_event()
    {
        $(".search-keyword").keyup(function(e)
        {
            search_keyword = $(e.currentTarget).val();
            page = 1;
            action_load_table();
        });
    }

    function add_event_change_tab()
    {
        $(".nav-tabs a").click(function(e)
        {
            page = 1;
            filter = $(e.currentTarget).attr("filter");
            $(".nav-tabs li").removeClass("active");
            $(e.currentTarget).closest("li").addClass("active");
            action_load_table();
        });
    }

    function add_event_change_sorted()
    {
        $(".sorted_by").change( function(e)
        {
            page        = 1
            sorted_by   = $(".sorted_by").val();
            action_load_table();
        });
    }

    function add_event_click_category()
    {
        $(".category").click( function(e)
        {
            if(!$(this).hasClass("active"))
            {
                $(".category").removeClass("active");
                $(this).addClass("active");
                $(".category_name").text($(this).text().toUpperCase());
                
                page     = 1;
                category = $(this).attr("id");
                action_load_table();
            }
        });
    }

    function action_load_table()
    {
    	// $(".show-loader").slideDown();

     //    if(account_ajax_loading)
     //    {
     //        table_ajax_loading.abort();
     //    }

     //    table_ajax_loading = $.ajax(
     //    {
     //        url: '/load_products',
     //        data: {page:page, sorted_by:sorted_by, category:category},
     //        traditional: true,
     //        type:"get",
     //        success: function(data)
     //        {
     //            $(".show-loader").slideUp();
     //            $(".load-table").hide().html(data).fadeIn(1500);
     //        },
     //        error: function(data)
     //        {
     //            console.log("ERROR! (canceled) :" + data);
     //        }
     //    });
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
