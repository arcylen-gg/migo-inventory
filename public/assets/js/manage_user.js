
$(document).ready(function(){
	$(document).on('click','#search_button_user',function()
	{
		var search_user = $('#search_user').val();
	
		$.ajax({
			type:'GET',
			url:'/pressadmin/manage_user_search',
			data:{
				search_user: search_user,
			},
			dataType:'text',
		}).done(function(data)
			{		
			   $('#showHere_table_search').html(data);
			});
    });
});










