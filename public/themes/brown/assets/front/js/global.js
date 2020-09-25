var global = new global();

function global()
{
  init();
  function init()
  {
    document_ready();
    window_load();
  }
  function document_ready()
  {
    event_match_height();
    // event_account_tab();
    event_show_cart();
    
    // event_search();
  }
  function window_load()
  {
    // $(window).load(function() 
    // {
    //   event_loader();
    // });
    // window.onbeforeunload = function()
    // {
    //   leave_page();
    // };
  }
  function event_loader()
  {
    $(".loader-container").fadeOut();
    // $(".loader-container").hide();
  }
  function leave_page()
  {
    // $(".loader-container").fadeIn();
  }
  function event_match_height()
  {
  	$('.match-height').matchHeight();
  }
  function event_account_tab()
  {
    $('body').on('click', '.account-modal .account-tab .holder', function(event) 
    {
      action_account_tab(event);
    });

    $('body').on('click', '.account-modal-button', function(event) 
    {
      action_account_tab(event)
      action_show_account_modal(event)
    });
  }
  function action_account_tab(event)
  {
    event.preventDefault();

    var type = $(event.currentTarget).attr("type");

    $('.account-modal .account-tab .holder').removeClass('active');
    $('.account-modal .account-tab .holder[type="'+type+'"]').addClass('active');
    $('.account-modal .account-content').addClass('hide');
    $('.account-modal .account-content[type="'+type+'"]').removeClass('hide');

    $(window).resize();
  }
  function action_show_account_modal()
  {
    $('#account_modal').modal();
  }
  function event_show_cart()
  {
    $('body').on('click', '.cart-holder', function(event) 
    {
      action_show_cart(event);
    });
  }
  function action_show_cart(event)
  {
    event.preventDefault();
    
    $("#cart_modal").modal();
  }
  function event_search()
  {
    $('#search_field').bind("enterKey",function(e)
    {
      location.href='/search/'+$(this).val()+'?search='+$(this).val();
    });
    
    $("#search_field").keyup(function(e)
    { 
      if(e.keyCode == 13)
      {
        $(this).trigger("enterKey");
      }
    });
  }
}