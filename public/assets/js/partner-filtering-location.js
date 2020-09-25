$(document).ready(function(){
	$('#locationDropdown').on('change', function(e){
		var locationVal = $(this).val();
		$.ajax({
			type: 'GET',
			url: '/partner-filtering-location',
			dataType: 'json',
			data: {locationVal: locationVal}
			}).done(function(data){
				$('.partner-result').html(data);
			});
		
	});
});