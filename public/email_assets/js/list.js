$(function () {
    $('.list-group .list-group-item .tocheck').each(function () {
        
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

   
    $(document).ready(function(){
    $('#check').click(function(event) {
    });
    $checks = $(":checkbox");
    $checks.on('change', function() {
        var string = $checks.filter(":checked").map(function(i,v)
        {
            var email = $('.email_add').val();
            var data_container = this.closest(".list-group-item");
            return  $(data_container).text();
        }).get();
        $('.input_chose_recipient').val(string);
    }).trigger('change');
});

  


    function selectall()
    {
    $('.to_check').prop('checked','checked')
    $checks = $(":checkbox");
    $checks.on('change', function() {
        var string = $checks.filter(":checked").map(function(i,v)
        {
            var data_container = this.closest(".list-group-item");
            return  $(data_container).text();
        }).get();
        $('.input_chose_recipient').val(string);
    }).trigger('change');
    }

   /* function click_checkbox()
    {
         $checks = $(":checkbox");
    $checks.on('change', function() {
        var string = $checks.filter(":checked").map(function(i,v)
        {
            var data_container = this.closest(".list-group-item");
            return  $(data_container).text();
        }).get();
        $('.input_chose_recipient').val(string);
    }).trigger('change');
}           */
    

  /*function selectall(source) {
  checkboxes = document.getElementsByName('foo');
  alert($(checkboxes).text());
  $('input[type="checkbox"]').attr("checked", "checked");
  for(var i=0, n=checkboxes.length;i<n;i++) {
    this.checked = true;   
    checkboxes[i].checked = source.checked;
    alert('123s');
      
    }
}*/

  

/*$( '.recipient_container .check-all' ).click( function () {
    alert('123');
    $( '.recipient_container2 input[type="checkbox"]' ).prop('checked', this.checked)
  })
*/

