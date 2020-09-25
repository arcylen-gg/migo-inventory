
$(document).ready(function(){
	$(document).on('click','#search_button',function()
	{
		alert('Hello User');
		var search_media = $('#search_media').val();
	
		$.ajax({
			type:'GET',
			url:'/mediacontacts/search',
			data:{
				search_media: search_media,
			},
			dataType:'text',
		}).done(function(data)
			{
			    alert('Hello User');		
				$('#showHere_table1').html(data);
		});
    });
});










