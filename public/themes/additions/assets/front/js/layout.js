var layout = new layout();

function  layout() {
	console.log("hi");
	init();

	function init() {
		_ready();
		customDropDown();
	}
	function _ready() {
		
		modalFunction();
		product_hover_style();
	}
	function modalFunction(){
		$(".account-modal-button").unbind("click");
		$(".account-modal-button").bind("click", function(){
			modalOperation();
		});
	}
	function modalOperation(){
		$(".btn-create-acount").unbind("click");
		$(".btn-create-acount").bind("click", function(){
			// var username = $("#new_username").val();
			validate_and_create_account();
		});
		
		$(".btn-signin").unbind("click");
		$(".btn-signin").bind("click", function() {
		var username = $("#login_username").val();
		var password = $("#login_password").val();
		if(username != '' && password != ''){
			var datas = [];
			datas = {
				_token:token(),
				username:username,
				password:password
			};
			var urls = '/login';
			$(".btn-signin").html('SIGNING...');
			$.ajax({
				url 	 : urls,
				type 	 : "POST",
				data 	 : 	datas,
				success  : function(result){
					if(result == 'success'){
						$(".login-error").html(alert_div(result,'Success'));
						location.reload();
					}
					else{
						$(".login-error").html(alert_div('danger',result));
					}
					$(".btn-signin").html('SIGN IN');
				},	
				error  	 : 	function(err){
					$(".login-error").html(alert_div('danger','Error something went wrong.'));
					$(".btn-signin").html('SIGN IN');
				}
			});
			// var result = ajax_user(url, data);
		}
		else{
			$(".login-error").html(alert_div('danger',"Please complete the missing field/s."));
		}
			
		});
	}
	
	function validate_and_create_account()
	{
		var email = $("#new_email").val();
		var bday = $("#new_bday").val();
		var address = $("#new_address").val();
		var country = $("#new_country").val();
		var province = $("#new_province").val();
		var city = $("#new_city").val();
		var zip_code = $("#new_zip_code").val();
		var number = $("#new_number").val();
		var password = $("#new_password").val();
		var cpassword = $("#new_cpassword").val();
		var firstname = $("#new_firstname").val();
		var lastname = $("#new_lastname").val();
		var msg = '';
		if(lastname != '' && firstname != '' && email != '' && bday != '' && address != '' && country != '' && province != '' && city != '' && zip_code != '' && number != '' && password != '' && cpassword != ''){
			if(password == cpassword){
				$(".btn-create-acount").html('CREATING...');
				var datas = [];
				datas = {
					first_name:firstname,
					last_name:lastname,
					email:email,
					b_day:bday,
					_address:address,
					phone:number,
					password:password,
					password_confirmation:cpassword,
					country:country,
					province:province,
					city:city,
					zip_code:zip_code,
					_token:token()
				};
				var urls = '/create_account';
				$.ajax({
					url 	: urls,
					type 	: "POST",
					data  	: datas,
					success : function(result){
						if(result == 'created'){
							location.reload();
						}
						else{
							$(".error-div-create").html(alert_div('danger',result));
						}
						$(".btn-create-acount").html('CREATE');

					},
					error 	: function(err){
						$(".error-div-create").html(alert_div('danger','Error, something went wrong.'));
						$(".btn-create-acount").html('CREATE');
					}
				});
				// alert(result);
			}
			else{
				msg = 'Password did not match';
				$(".error-div-create").html(alert_div('danger',msg));
			}
		}
		else{
			msg = 'Please complete the missing field';	
			$(".error-div-create").html(alert_div('danger',msg));
		}
	
	}

	// function ajax_user(urls = '', datas){
	// 	var res = '';
	// 	$.ajax({
	// 		url 	: urls,
	// 		type 	: "POST",
	// 		data 	: datas,
	// 		success : function (result) {
	// 			console.log(result);
	// 			res =  result;
	// 		},
	// 		error 	: function(err){
	// 			res = 'error';
	// 		}
	// 	});
	// }


	function  alert_div(str = '',msg = '') {
		var div = ' <div class="alert alert-'+str+'">';
            div += '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
            div += msg;
            div += '</div>';
         return div;
	}

	function  token() {
		return $("#_token").val();
	}

	function product_hover_style()
	{
		$(document).on("mouseenter",".list-product", function()
		{
			var id = $(this).attr("id");
			$(".cart-hidden"+id+"").removeClass("hidden");
		});
		
		$(document).on("mouseleave",".list-product", function()
		{
			var id = $(this).attr("id");
		    $(".cart-hidden"+id+"").addClass("hidden");
		});
		
		$(document).on("mouseenter",".bag", function()
		{
			$(".bag").attr("src", "/themes/additions/front/bag-hovered.png")
		});
		
		$(document).on("mouseleave",".bag", function()
		{
			$(".bag").attr("src", "/themes/additions/front/bag-black.png")
		});
		
		$(document).on("mouseenter",".magnify-glass", function()
		{
			$(".magnify-glass").attr("src", "/themes/additions/front/magnify-hovered.png")
		});
		
		$(document).on("mouseleave",".magnify-glass", function()
		{
			$(".magnify-glass").attr("src", "/themes/additions/front/magnify-black.png")
		});
	};
	
	function customDropDown()
	{
		$(window).click(function() {
			$(".dropdown-tracking").fadeOut("fast");
		});

		$(".track-record").unbind("click");
		$(".track-record").bind("click", function(event)
		{
			$(".dropdown-tracking").fadeToggle("fast");
			event.stopPropagation();
		});
	}
}