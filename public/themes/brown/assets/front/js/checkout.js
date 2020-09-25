var checkout = new checkout();

function checkout(){
	init();
	function init(){
		_ready();
		
	}
	
	function _ready(){
		check();
		shipping();
		payment();
		files_change();
		change_account_radio_btn();
		validator();
	}
	
	function validator(){
		var password = document.getElementById("inputPassword")
		  , confirm_password = document.getElementById("confirm_password");
		
		function validatePassword(){
		  if(password.value != confirm_password.value) {
		    confirm_password.setCustomValidity("Passwords Don't Match");
		  } else {
		    confirm_password.setCustomValidity('');
		  }
		}
		
		password.onchange = validatePassword;
		confirm_password.onkeyup = validatePassword;
	}
	
	function check(){
		$("#btn-submit").unbind("click");
		$("#btn-submit").bind("click", function(){
			// form_checkout
		});
	}
	
	function shipping(){
		$(".shippingmethod").change(function()
		{
			var shippingprice = $(this).find(':selected').data("price");
			var subtotal = $(".hidden-table-sub-total").val();
			var total = parseFloat(shippingprice) + parseFloat(subtotal);
			$(".table-total").html("P " + strmoney(total));
			$(".hidden-table-total").html(total);
			$(".table-ship-fee").html("P " + strmoney(shippingprice));
			$(".hidden-table-ship-fee").val(shippingprice);
			
		});
		var shippingprice = $(".shippingmethod").find(':selected').data("price");
		if(shippingprice == undefined){
			shippingprice = 0;
		}
		var subtotal = $(".hidden-table-sub-total").val();
		$(".table-ship-fee").html("P " + strmoney(shippingprice));
		var total = parseFloat(shippingprice) + parseFloat(subtotal);
		$(".table-total").html("P " + strmoney(total));
	}
	
	function payment(){
		$(".radio-payment").unbind("change");
		$(".radio-payment").bind("change", function(){
			var val = $(this).val();
			var display = '';
			var bank = '';
			var remittance = '';
			var actions = '/';
			switch(val){
				case 'Paypal':
					display = 'none';
					actions	= '/payment/getCheckout';
					// $("#file_proof").attr('required',false);
					break;
				case 'Bank Deposit':
					display = 'block';
					bank = 'block';
					remittance = 'none';
					// $("#file_proof").attr('required',true);
					actions	= '/checkout/bankremittance';
					break;
				case 'Remitance':
					display = 'block';
					bank = 'none';
					remittance = 'block';
					// $("#file_proof").attr('required',true);
					actions	= '/checkout/bankremittance';
					break;
				case 'Cash On Delivery':
					display = 'none';
					// $("#file_proof").attr('required',false);
					actions	= '/checkout/bankremittance';
					break;
			}
			$(".div-bank-select").css("display",bank);
			$(".div-remittance-select").css("display", remittance);
			$(".upload").css("display",display);
			$("#form_checkout").attr("action",actions);
			// document.form_checkout.action = actions;
		});
		$("#bank").unbind("change");
		$("#bank").bind("change", function (){
			var id = $(this).val();
			$(".bank-tbl").css("display","none");
			$("#bank-"+id).css("display","table-row-group");
		});
		$("#remittance").unbind("change");
		$("#remittance").bind("change", function(){
			var id = $(this).val();
			$(".tbl-remittance").css("display","none");
			$("#remittance-"+id).css("display","table-row-group");
		});
	}
	function files_change(){
		$("#file_proof").unbind("change");
		$("#file_proof").bind("change", function(){
			var file = document.getElementById('file_proof').files[0];
			$(".file_name").html(file.name);
		});
	}
	function strmoney(amount = 0){
		amount = parseFloat(amount);
		var str = amount.toFixed(2).replace(/./g, function(c, i, a) {
		    return i && c !== "." && ((a.length - i) % 3 === 0) ? ',' + c : c;
		});
		return str;
	}
	
	function change_account_radio_btn()
	{
		$(".create_account").unbind("change");
		$(".create_account").bind("change", function(){
			radio_value = $(this).val();
			if(radio_value == 0)
			{
				$(".password-container").fadeIn();
				$("#inputPassword").attr("required",true);
				$("#confirm_password").attr("required",true);
			}
			else
			{
				$(".password-container").fadeOut();
				$("#inputPassword").attr("required",false);
				$("#confirm_password").attr("required",false);
			}
		});
		// $("input:radio").change( function()
		// {
		// 	radio_value = $(this).val();
			
		// 	if(radio_value == 0)
		// 	{
		// 		$(".password-container").fadeIn();
		// 		$("#inputPassword").attr("required",true);
		// 		$("#confirm_password").attr("required",true);
		// 	}
		// 	else
		// 	{
		// 		$(".password-container").fadeOut();
		// 		$("#inputPassword").attr("required",false);
		// 		$("#confirm_password").attr("required",false);
		// 	}
		// });
	}
}