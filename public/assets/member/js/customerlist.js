
function submit_done_customer(result){
	result = JSON.parse(result);
	var customer_info = result.customer_info;
	customerlist.loadcustomer();
}


var customerlist = new customerlist();
var load_ajax_customer = null;
var item_search_delay_timer = {};
var customer_category = 'all';
var customer_category_type = 'all';
var customer_search_keyword = null;
var is_archived = 0;
function customerlist()
{
	init();

	function init()
	{
		search();
		tabsearch();
		action();
		filter_category();
	}
	function  filter_category() 
	{
		$('.select-category').on("change",function()
		{
			customer_category = $(this).val();
			search(customer_category, customer_category_type, customer_search_keyword, is_archived);
		});
		$('.select-category-type').on("change",function()
		{
			customer_category_type = $(this).val();
			search(customer_category, customer_category_type, customer_search_keyword, is_archived);
		});
		$("body").on("change",'.customer-search', function ()
		{
			customer_search_keyword = $(this).val();
			search(customer_category, customer_category_type, customer_search_keyword, is_archived);
		});
	}
	function search(category = 'all', category_type = 'all', search_keyword = null, set_is_archived = 0)
	{
		 var data = {
		 	customer_category:category,
		 	customer_category_type:category_type,
		 	str:search_keyword,
		 	archive:set_is_archived,
		 	_token:misc('_token')
		 };
		 var url = "/member/customer/loadcustomer";
		 var target = ".panel-customer";
		 
		if(load_ajax_customer)
		{
			load_ajax_customer.abort();
		}

		clearTimeout(item_search_delay_timer);
		item_search_delay_timer = setTimeout(function()
		{					
			 search_ajax(url, data, target);
		}, 500);
	}
	function tabsearch()
	{	
		$(".customer-tab").unbind("click");
		$(".customer-tab").bind("click", function (){
			is_archived = $(this).attr("data-value");
			search(customer_category, customer_category_type, customer_search_keyword, $(this).attr("data-value"));
			// $search = $(".customer-search").val();
			// var data = {
			// 	archive:value,
			// 	strt : $search,
			// 	_token:misc('_token')
			// };
			// var url = "/member/customer/loadcustomer";
			// var target = ".panel-customer";
			// search_ajax(url, data, target);
		});
	}

	function search_ajax(urls = "", datas = [], target = "")
	{
		$(target).html(misc('loader-16-gray margin-top-20'));
		$.ajax({
			url 	: 	urls,
			data 	: 	datas,
			type 	: 	"POST",
			success : 	function(result){
				$(target).html(result);
				action();
			},
			error 	: 	function(err){
				toastr.error("Error, something went wrong.");
			}
		});
	}

	this.loadcustomer = function(){
		loadcustomer();
	}

	function loadcustomer(str = '')
	{
		$(".panel-customer").html('<div class="loader-16-gray margin-top-20" style="margin-top:50px"></div>');
		load_ajax_customer = $.ajax({
			url 	: 	"/member/customer/loadcustomer",
			type 	: 	"POST",
			data 	: 	{
				_token:misc('_token'),
				str:str
			},
			success : 	function(result){
				$(".panel-customer").html(result);
				action();
			},
			error 	: 	function(err){
				toastr.error("Error, something went wrong.");
			}
		});
	}

	this.action = function(){
		action();
	}

	function action()
	{
		$(".active-toggle").unbind("click");
		$(".active-toggle").bind("click", function(){
			var id = $(this).data("content");
			var html = $(this).data("html");
			var con = confirm("Are you sure you want to make this client "+html+"?");
			var btn = $(".btn-action-"+id);
			var target = $(this).data("target");
			var btn_html = btn.html();
			var value = $(this).data("value");
			
			if(con){
				btn.html(misc('spinner'));
				$.ajax({
					url 	: 	"/member/customer/inactivecustomer",
					type	: 	"POST",
					data 	: 	{
						id:id,
						archived:value,
						_token:misc('_token')
					},
					success : 	function(result){
						$("#tr-customer-"+id).remove();
						toastr.success("Customer has been inactive.");
					},
					error 	: 	function(err){
						toastr.error("Error, something went wrong.");
						btn.html(btn_html);
					}
				});
			}
		});
	}
	function misc(str = ''){
		switch(str){
			case 'spinner':
				return '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>';
				break;
			case '_token':
				return $("#_token").val();
				break;

			case 'loader-16-gray':
                return '<div class="loader-16-gray"  style="margin-top:50px"></div>';
                break;

             case 'loader-16-gray margin-top-20':
                return '<div class="loader-16-gray margin-top-20"  style="margin-top:50px"></div>';
                break;
		}
	}
}

function loading_done_paginate(){
	customerlist.action();	
}