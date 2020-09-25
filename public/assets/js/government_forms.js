var government_forms = government_forms()
var ajaxdata = {};

function government_forms()
{
	
	init();

	function init()
	{
		$(document).ready(function()
		{
			document_ready();

		})
		
	}
	function document_ready()
	{
		action_load_table_hdmf();
		action_load_table_sss();
		action_load_table_philhealth();
	}
    function action_load_table_hdmf()
	{    
		$(".filter-by-company-hdmf").on("change", function(e)
		{
			var company_id 		= $(e.currentTarget).val();
			var month      		= $(this).attr("data-id");
			var year 			= $(".year").val();
			ajaxdata.company_id = company_id;
			ajaxdata.month      = month;
			ajaxdata._token 	= $("._token").val();
			ajaxdata.year		= year;
			$('#spinningLoader').show();
			$(".load-filter-data").hide();
			setTimeout(function(e){
			$.ajax(
			{
				url:"/member/payroll/reports/government_forms_hdmf_filter",
				type:"post",
				data: ajaxdata,
				
				success: function(data)
				{
					$('#spinningLoader').hide();
					$(".load-filter-data").show();
					$(".load-filter-data").html(data);
					// alert(data);
				}
			});
			}, 700);
		});
	}
	function action_load_table_sss()
	{    
		$(".filter-by-company-sss").on("change", function(e)
		{
			var company_id 		= $(e.currentTarget).val();
			var month      		= $(this).attr("data-id");
			var year 			= $(".year").val();
			ajaxdata.company_id = company_id;
			ajaxdata.month      = month;
			ajaxdata._token 	= $("._token").val();
			ajaxdata.year		= year;
			$('#spinningLoader').show();
			$(".load-filter-data").hide();
	        setTimeout(function(e){
			$.ajax(
			{
				url:"/member/payroll/reports/government_forms_sss_filter",
				type:"post",
				data: ajaxdata,
				
				success: function(data)
				{
					$('#spinningLoader').hide();
					$(".load-filter-data").show();
					$(".load-filter-data").html(data);
					// alert(data);
				}
			});
			}, 700);
		});
	}
	function action_load_table_philhealth()
	{    
		$(".filter-by-company-philhealth").on("change", function(e)
		{
			var company_id 		= $(e.currentTarget).val();
			var month      		= $(this).attr("data-id");
			var year 			= $(".year").val();
			ajaxdata.company_id = company_id;
			ajaxdata.month      = month;
			ajaxdata._token 	= $("._token").val();
			ajaxdata.year		= year;
			$('#spinningLoader').show();
			$(".load-filter-data").hide();
	        setTimeout(function(e){
			$.ajax(
			{
				url:"/member/payroll/reports/government_forms_philhealth_filter",
				type:"post",
				data: ajaxdata,
				
				success: function(data)
				{
					$('#spinningLoader').hide();
					$(".load-filter-data").show();
					$(".load-filter-data").html(data);
					// alert(data);
				}
			});
			}, 700);
		});
	}
}