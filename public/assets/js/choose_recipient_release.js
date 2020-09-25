
$(document).ready(function () 
{ 
	$(document).on('click',"#select_all",function()
	{	
		var $checkboxes = $('#choose_recipient_form td input[name=checkbox]').prop('checked', true);
        var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
        $('#Chosen_total').text(countCheckedCheckboxes);
	});
});	

$(document).ready(function () 
{
	$(document).on('click',"#unselect_all",function()
	{	
		var $checkboxes = $('#choose_recipient_form td input[name=checkbox]').prop('checked', false);
		var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
        $('#Chosen_total').text(countCheckedCheckboxes);
	});
});	

$(document).ready(function()
{
    var $checkboxes = $('#choose_recipient_form td input[type="checkbox"]'); 
    $checkboxes.change(function()
    {
        var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
        $('#Chosen_total').text(countCheckedCheckboxes);
    });
});

$(document).ready(function(){
	$(document).on('click','#search_button',function()
	{
		var search_key = $('#search_key').val();
	
		$.ajax({
			type:'GET',
			url:'/pressuser/pressrelease/recipient/search',
			data:{
				search_key: search_key,
			},
			dataType:'text',
		}).done(function(data)
			{		
				$('#showHere_table').html(data);
		});
    });
});

$(document).ready(function(){

	$(document).on('click','#recipient_button',function()
	{
    	var name_array = [];
    	var email_array = [];
    	var ctr = 0;
	   $('input.recipient_checkbox:checkbox:checked').each(function ()
	    {
	    	var data = $(this).val();
	    	var name =$('.rec_name_'+data).text();
	    	var email =$('.rec_email_'+data).text();
	    	name_array[ctr] = name;
	    	email_array[ctr] = email;
	    	ctr++; 

  		});
	   		$('#results_number').text(ctr+"  Chosen Recipients");
	   		$('#results_number_sendto').text(ctr+"  Chosen Recipients");
	    	$('#recipient_name').text(name_array);
	    	$('#recipient_email').val(email_array);
	    	$("#global_modal").modal('hide');
	    	 
    });
});








