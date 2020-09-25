var global_recipient_id = null;
$(document).ready(function(){
	$('#get_data_tinymce').submit(function(e){
		var ajaxdata = $("._token1").val();
		var content = tinymce.get('texteditor').getContent();

		$('#get_data_tinymce').ajaxSubmit({
		     type: "POST",
		     url: '/member/page/press_release_email/save_email_press_release',
		     data: {ajaxdata:ajaxdata,content:content},
		     dataType:"json",
		     success: function(response){
		         if(response == 'success')
		         {
		         	$('.title_email').val('');
              $('.title').val('');
		         	$('.subject_email').val('');
		         	tinymce.get('texteditor').setContent('');
		         	alert('message save');
		         }
		     }
		});

		return false;
	});
});

  $('.inputsubmit').click(function(e)
  {
    $('#RecipientModal').modal('show');		


		var country = $('#country').val();
		var title_of_journalist = $('#title_of_journalist').val();
    var industry_type = $('#industry_type').val();
    alert(industry_type);
  	var data = {title_of_journalist:title_of_journalist,country:country, industry_type:industry_type};
		var url = addParams("/member/page/press_release_email/choose_recipient_press_release",data);
		$(".recipient_container").load(url+" .recipient_container2",function(){
			$.getScript("/email_assets/js/list.js");
      /*$.getCss("/email_assets/email_css/create_email.css");*/
    /*var myStylesLocation = "/email_assets/email_css/create_email.css";

      $.get(myStylesLocation, function(css)
    {
   $('<style type="text/css"></style>')
      .html(css)
      .appendTo("header");
      alert('123');
    });*/
});   

});

var global_recipient_id = null;
$(document).ready(function(){
  $('#get_data_tinymce1').submit(function(e){
    var ajaxdata = $("._token1").val();
    var content = tinymce.get('texteditor').getContent();

    $('#get_data_tinymce1').ajaxSubmit({
         type: "POST",
         url: '/member/page/press_release_email/send_press_release',
         data: {ajaxdata:ajaxdata,content:content},
         dataType:"json",
         success: function(response){
             if(response == 'success')
             {
              /*$('#myModal_email').modal('hide');*/
              /*$('.from_email').val('');
              $('.to_email').val('');*/
              $('.subject_email').val('');
              tinymce.get('texteditor').setContent('');
              alert('message sent');
             }
         }
    });

    return false;
  });
});




		/*$(document).ajaxComplete(function(){*/
		  /*$.getScript('/email_assets/js/list.js', function() {
		  alert('Load was performed.');*/
		/*});*/
		



		// $.ajax({
		// 	type: 'GET',
		// 	url: '/member/page/press_release_email/search_recipient_press_release',
		// 	data: {recipient_group: recipient_group,recipient_name:recipient_name,recipient_email_address:recipient_email_address,recipient_position:recipient_position},
		// 	success:function(data)
		// 	{
		// 		$('.list').html(data);
		// 	}
		// });


	

  /*check_list();
  function check_list()
  {	
  	$(function () {
    $('.list-group.checked-list-box .list-group-item').each(function () {
        
        // Settings
        var $widget = $(this),
            $checkbox = $('<input type="checkbox" class="hidden" />'),
            color = ($widget.data('color') ? $widget.data('color') : "primary"),
            style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };
            
        $widget.css('cursor', 'pointer')
        $widget.append($checkbox);

        // Event Handlers
        $widget.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });
          

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $widget.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $widget.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$widget.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $widget.addClass(style + color + ' active');
            } else {
                $widget.removeClass(style + color + ' active');
            }
        }

        // Initialization
        function init() {
            
            if ($widget.data('checked') == true) {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
            }
            
            updateDisplay();

            // Inject the icon if applicable
            if ($widget.find('.state-icon').length == 0) {
                $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
            }
        }
        init();
    });
    
    $('#get-checked-data').on('click', function(event) {
        event.preventDefault(); 
        var checkedItems = {}, counter = 0;
        $("#check-list-box li.active").each(function(idx, li) {
            checkedItems[counter] = $(li).text();
            counter++;
        });
        $('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
    });
});

  }
*/




/*var ajaxdata = {};
      $("body").on("click", ".pagination a", function(e)
      {
      $url = $(e.currentTarget).attr("href"); //get URL (string)
      var url = new URL($url); //convert format URL
       $(".recipient_container").load($url+" .recipient_container2",function(){
      $.getScript("/email_assets/js/list.js");
      });
      return false;
      });*/

  
  var addParams = function( url, data )
  {
    if ( ! $.isEmptyObject(data) )
    {
      url += ( url.indexOf('?') >= 0 ? '&' : '?' ) + $.param(data);
    }

    return url;
  }
  /*function action_load_table($url,$page)
  {
    // alert($url,$page);
    $.ajax(
    {
      type: 'GET',
      url: '/member/page/press_release_email/search_recipient_press_release',
      data: {shop_id: global_shop_id,page:$page},
      success:function(data)
      {
        $('.list').html(data);
      }
    
    });
*/



 