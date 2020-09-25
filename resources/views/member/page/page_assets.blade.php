<!-- <script type="text/javascript" src="/assets/member/js/tinymce.min.js"></script> -->
<script type="text/javascript">
tinymce.init({ 
	selector:'.mce',
	plugins: 'lists advlist',
	toolbar: 'bold italic underline | bullist numlist outdent indent',
	menubar:false,
	height:200, 
	content_css : "/assets/member/css/tinymce.css"
 });

function submit_selected_image_done(data) 
{
	if (typeof data.image_data[1] !== 'undefined') 
	{
		$('.maintenance-image-multiple-holder[key="'+data.akey+'"]').html('');

		$.each(data.image_data, function(index, val) 
		{
			$('.maintenance-image-multiple-holder[key="'+data.akey+'"]').append('<img src="'+val.image_path+'">');
		});
		
		$('.maintenance-image-input[key="'+data.akey+'"]').val(JSON.stringify(data.image_data));
	}
	else
	{
		$('.maintenance-image-holder[key="'+data.akey+'"]').html('<img src="'+data.image_data[0].image_path+'">');
		$('.maintenance-image-input[key="'+data.akey+'"]').val(data.image_data[0].image_path);
	}
}
</script>
<style type="text/css">
.maintenance-image-holder
{
	position: relative;
	padding-bottom: 50%;
	height: 0;
	background-color: #ddd;
	margin-bottom: 10px;
}
.maintenance-image-holder img
{
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	height: 100%;
	object-fit: cover;
}
.maintenance-image-multiple-holder img
{
	width: 150px;
	display: inline-block;
	margin: 5px;
	border: 1px solid #bcbcbc;
}
</style>