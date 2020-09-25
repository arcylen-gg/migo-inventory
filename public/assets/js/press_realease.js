$(document).ready(function () 
{
	$(document).on('#submit_button',function(){
	   
       alert('123');

	});
});
	
$(document).ready(function () 
{
	$(document).on("change","#choose_country",function()
	{
		var country = $(this).val();

		 $.ajax({
            type:'POST',
            url:'/pressuser/pressrelease/recipient',
            data:
            	{
            	country: country,
            	},
            dataType:'text',
            }).done(function(data)
            {
             	$('#country_table').show(data);
             	
            });
	});
});



