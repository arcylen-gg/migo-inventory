$(document).ready(function()
{
	$(document).on('click','#search_newsroom_btn',function()
	{
		var search_newsroom = $('#search_newsroom').val();
		
		$.ajax({
			type:'get',
			url:'/newsroom/search',
			data:{
				search_newsroom: search_newsroom,
			},
			dataType:'text',
		}).done(function(data)
			{	
				$('#show_newsroom').html(data);
			});
    });
});







