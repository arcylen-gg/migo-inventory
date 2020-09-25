var profile = new profile();

function  profile() {
	init();
	function init(){
		_ready();
	}
	function _ready(){
		event_click_edit_account();
		details();
	}	
	function details(){
		$(".btn-details").unbind("click");
		$(".btn-details").bind("click", function(){
			var id = $(this).data("content");
			var data = {
				id:id,
				_token:misc('token')
			};
			var url = '/brkhistory';
			var target = '.modal-history-details';
			ajax_result(data, url, target);
		});
	}

	function  ajax_result (datas , urls = '', target = '') {
		$(target).hide().html(misc('loader')).slideDown();
		// console.log(datas);
		$.ajax({
				url 	: 	urls,
				type 	: 	"POST",
				data 	: 	datas,
				success : 	function (result) {
					$(target).hide().html(result).fadeIn(1500);
				},
				error 	: 	function(err) {
					$(target).html(error_reload(datas,urls, target));
					Operation();
				}
			});
			
		
	}
	function  error_reload(data, urls, target) {
		data = JSON.stringify(data);
		var btn = '<button class="btn btn-success btn-reload" data-url="'+urls+'" data-datas=\''+data+'\' data-target="'+target+'"><i class="fa fa-recycle" aria-hidden="true"></i>&nbsp;Reload</button';
		return misc('error') + '<br>'+ btn;
	}
	function Operation() {
		$(".btn-reload").unbind("click");
		$(".btn-reload").bind('click', function() {
			var url = $(this).data('url');
			var data = $(this).data('datas');
			var target = $(this).data('target');
			console.log(data);
			// data = JSON.parse(data);
			ajax_result(data, url, target);
		});
	}
	
	function misc(str = ''){
		var error = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> <strong>Error!</strong> Something went wrong.</div>';
		switch(str){
			case 'loader':
				return '<img src="/assets/front/img/loader.gif" class="loader-modal">';
				break;
			case 'token':
				return $("#_token").val();
				break;
			case 'error':
				return error;
		}
	}
	
	function event_click_edit_account()
	{
		$(".btn-update").click(function ()
		{
			$this = $(this);
			$this.attr("disabled","disabled");
			$this.text("updating...");
			$.ajax(
			{
				url:'profile/update',
				type:'post',
				data:$("form").serialize(),
				
				success: function (data)
				{
					data = jQuery.parseJSON(data);
					
					if(data.status == 0)
					{
						$(".alert").text(data.message);
						$(".alert").removeClass("hide");
						$this.removeAttr("disabled");
						$this.text("update");
					}
					else
					{
						location.href="/profile";
					}
				},
				
				error: function (e)
				{
					$(".alert").text(e.error);
					$(".alert").removeClass("hide");
					$(this).removeAttr("disabled");
				}
			});	
		});
	}
	
}