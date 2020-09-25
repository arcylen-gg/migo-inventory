$(document).ready(function(){
	$('#locationDropdown').on('change', function(e){
		var locationVal = $(this).val();
		alert(locationVal);
		$('#spinningLoader').show();
		$('.partner-result').hide();

		setTimeout(function(e){
			$.ajax({
			type: 'GET',
			url: '/member/page/partnerview/partner-filter-by-location',
			dataType: 'json',
			data: {locationVal: locationVal}
			}).done(function(data){
				$('#spinningLoader').hide();
				$('.partner-result').show();
				$('.partner-result').html(data);
			});
		}, 700);
	});
});