var vendor = new vendor();
var status = 0;
function vendor() 
{
    init();
    
    function init()
    {
        document_ready();
    }

    function document_ready() 
    {
    	action_check_same_as_bill();
    	//action_same_billing_with_shipping();

    	initialize_select_plugin();
    	action_date_picker();
    	action_shipping_readonly();
    	action_display_name_as();

    	event_same_as_billing();
    	event_button_toggle();
    	event_listdropdownname_click();
    	onchange_search();
    	upload_attachment();
    	removeupload();
    	add_bank_record();
    }
    function add_bank_record()
    {
    	$("body").on("click",".btn-add-line", function()
		{
			$(".form-group-bank-records").append($(".div-script .body-script").html());
		});
		$("body").on("click",".btn-remove-line", function()
		{		
			var len = $(".form-group-bank-records .btn-remove-line").length;
			if($(".form-group-bank-records .btn-remove-line").length > 1)
			{
				$(this).parent().parent().remove();
			}
		});
    }
    function  misc(str) 
    {
        switch (str)
        {
            case '_token':
                return $("#_token").val();
                break;
            case 'spinner':
                return '<i class="fa fa-spinner fa-pulse fa-fw"></i><span class="sr-only">Loading...</span>';
                break;
            case 'loader-16-gray':
                return '<div class="loader-16-gray"></div>';
                break;
             
            case 'times':
            	return '<i class="fa fa-times" aria-hidden="true"></i>';
            	break;
        }
    }
	    var filecount = 0;
    function upload_attachment()
    {
       $("body").on("click",".div-file-input", function()
       	{
	        document.getElementById('attachment_file').click();
       	});
	    $("body").on("change","#attachment_file", function()
       	{
	        var file = document.getElementById("attachment_file").files[0];
	        var filename = file['name'];
	        var formdata = new FormData();
	        var ajax = new XMLHttpRequest();
	        formdata.append("file", file);
	        formdata.append("_token",misc('_token'));
	        ajax.upload.addEventListener("progress", UploadProgress, false);
	        ajax.addEventListener("load", LoadUpload, false);
	        ajax.open("POST","/member/vendor/upload-file");
	        ajax.send(formdata);
	        filecount++;
	        var htlm = '<div class="form-group file-'+filecount+'">';
                htlm += '<div class="col-md-8">';
                htlm += '<span>'+filename+'</span>';
                htlm += '</div>'
                htlm += '<div class="col-md-4 file-operation-'+filecount+'">';
                htlm += '<div class="custom-progress-container container-'+filecount+'">';
                htlm += '<div class="custom-progress progress-'+filecount+'"></div>';
                htlm += '</div></div></div>';
             $(".div-attachment").append(htlm);
	        
	    });

    }
    function UploadProgress(Event)
    {
    	var progress = (Event.loaded  / Event.total) * 100;
    	$(".progress-"+filecount).css("width",progress + "%");
    	console.log(".progress-"+filecount);
    }
    function LoadUpload(Event)
    {
    	var result = Event.target.responseText;
    	$(".file-operation-"+filecount).html(cancelupload(filecount, result));
    	removeupload();
    }
    
    function cancelupload(num = 0, result = []){
    	console.log(result);
    	result = JSON.parse(result);
    	var html = '<a href="javascript:" class="pull-right remove-upload" data-value="0" data-path="'+result.url+'" data-target=".file-'+num+'"><i class="fa fa-times" aria-hidden="true"></i></a>';
    	html += '<input type="hidden" value="'+result.url+'" name="fileurl[]">';
    	html += '<input type="hidden" value="'+result.original+'" name="filename[]">';
    	html += '<input type="hidden" value="'+result.mimetype+'" name="mimetype[]">';
    	return html;
    }
    
    function removeupload()
    {
    	$("body").on("click",".remove-upload", function()
    	{
    		var target = $(this).attr("data-target");
    		var value = $(this).attr("data-value");
    		if(value == undefined){
    			value = 0;
    		}
    		var path = $(this).attr("data-path");
    		var con = confirm("Are you sure you want to remove this file?");
	    	if(con){
	    		$(this).html(misc('spinner'));
	    		$.ajax({
	    			url 	:	"/member/vendor/remove-file",
	    			type	:	"POST",
	    			data	:	{
	    				path:path,
	    				value:value,
	    				_token:misc('_token')
	    			},
	    			success :	function(result)
	    			{
	    				result = JSON.parse(result);
	    				if(result.result == 'error'){
	    					toastr.error(result.message);
	    					$(this).html(misc('times'));
	    				}
	    				else{
	    					
	    					$(target).remove();
	    				}
	    			},
	    			error	:	function(error){
	    				toastr.error("Error, please try again.");
	    				$(this).html(misc('times'));
	    			}
	    		});
	    		
	    	}
    	});
    }
	    
    function action_date_picker()
    {
    	$( ".datepicker" ).datepicker();
    }

    function action_check_same_as_bill()
    {
    	$(".same_as_billing").on("change",function()
    	{
			if (this.checked )
			{
				console.log('click to checked');
	            $(".ship-street").val($(".bill-street").val());
	            $(".ship-city").val($(".bill-city").val());
	            $(".ship-state").val($(".bill-state").val());
	            $(".ship-zipcode").val($(".bill-zipcode").val());

	            var bill_country = $(".bill-country").val(); 
				$(".ship-country").val(bill_country).change();
	        }
	        else
	        {
	        	console.log('click to unchecked');
	        }  
	        //action_same_billing_with_shipping();
	    	action_shipping_readonly();
		});
	    //action_shipping_readonly();  
    }

    function event_same_as_billing()
    {
	    $("body").on("change", ".billing-address", function(){
	        action_copy_billing_to_shipping($(this));
	    });
	    $("body").on("change", ".billing-country", function(){
	        action_copy_billing_to_shipping($(this));
	    });

	    $("body").on("change", ".same_as_billing", function(){
	        action_shipping_readonly();
	    });	
	}

	function action_copy_billing_to_shipping($this)
	{
		if($(".same_as_billing").is(":checked"))
	    {
	    	console.log('checked');
			var value 	= $this.val();
	        var target 	= $this.attr("data-target");
	        $(target).val(value).change();
    	}
    	else
    	{
    		console.log('not checked');
    	}
	}

	/*function action_same_billing_with_shipping()
	{

		if($(".same_as_billing").is(":checked"))
	    {
	    	console.log('checked');
	    	$(".bill-street").bind('keyup',function(e)
			{
				$('.ship-street').val($(e.currentTarget).val());
			});
			$(".bill-city").bind('keyup',function(e)
			{
				$('.ship-city').val($(e.currentTarget).val());
			});
			$(".bill-state").bind('keyup',function(e)
			{
				$('.ship-state').val($(e.currentTarget).val());
			});
			$(".bill-zipcode").bind('keyup',function(e)
			{
				$('.ship-zipcode').val($(e.currentTarget).val());
			});
			$(".bill-country").bind('keyup',function(e)
			{
				$('.ship-country').val($(e.currentTarget).val()).change();
			});
			$(".bill-country").bind('keyup',function(e)
			{
				var ship = $('.ship-country').val($(e.currentTarget).val()).change();
				console.log(ship + 'ship');
			});
    	}
    	else
    	{
    		console.log('not checked');
    		$(".bill-street").unbind('keyup');
			$(".bill-city").unbind('keyup');
			$(".bill-state").unbind('keyup');
			$(".bill-zipcode").unbind('keyup');
			$(".bill-country").unbind('keyup');
    	} 
	}*/

	function action_shipping_readonly()
	{
		if($(".same_as_billing").is(":checked"))
        {
            $(".shipping-container").find(".ship").attr("readonly", true);
		}
        else
        {
        	$(".shipping-container").find(".ship").attr("readonly", false);
        }
	}

	function event_button_toggle()
	{
	    $(document).on("click", ".btn-toggle-custom", function (e) {
	        var target = $(this).attr("data-target");
	        var container = $(this).attr("container");
	        $(".btn-toggle-custom").each(function(){
	            var target2 = $(this).attr("data-target");
	            if(target != target2){
	                $(target2).css("display","none");
	            }
	        });
	        e.stopPropagation();
	        $(target).slideToggle("fast");
	    })
	}
            	
    function action_display_name_as()
    {
	    $(document).on("change", ".auto-name", function(){
	    	var  combonames = comboname();
			$(".drop-down-display-name").html(combonames['html']);
			$(".display-name-check").val(combonames['combo'][0]);
	    });
	}

	function comboname(){
		var title = $(".title").val();
		var first_name = $(".first_name").val();
		var middle_name = $(".middle_name").val();
		var last_name = $(".last_name").val();
		var last_name2 = last_name;
		var suffix = $(".suffix").val();
		
		var title_suffix = title + suffix + last_name;;
		
		
		if(title != ""){
			title = title + ' ';
		}
		if(first_name != ""){
			first_name = first_name + ' ';
		}
		if(middle_name != ""){
			middle_name = middle_name + ' ';
		}
		
		var combo = [];
		combo[0] = title + first_name + middle_name + last_name + suffix;
		combo[1] = first_name + last_name2;
		combo[2] = last_name2 + ', ' + first_name;
		var html = '';
		var min = 0;
		var max = 2;
		
		if(first_name != '' && middle_name != "" && title_suffix != ""){
			min = 0;
			max = 2;
		}
		else{
			min = 0;
			max = 0;
		}
		for(var i = min; i <= max; i++){
			html += '<a href="#" class="list-group-item list-drop-display-name" data-html="'+combo[i]+'">'+combo[i]+'</a>';
		}

		var returns = [];
		returns['combo'] = combo;
		returns['html'] = html;
		return returns;
	}

	function event_listdropdownname_click()
	{
	    $(document).on("click", ".list-drop-display-name", function(){
	        var html = $(this).attr("data-html");
	        $(".txt-display-name").val(html);
	        $(".drop-down-display-name").slideToggle("fast");
	    });
	}
	function onchange_search()
	{
		$("body").on("change",".vendor-search", function()
		{
			search_vendor();
		});

		$("body").on("click",".vendor-tab", function()
		{
			status = $(this).attr("data-value");
			search_vendor();
		});
	}
	function search_vendor()
	{
		$(".load-vendor").css("opacity", 0.3);
		$.ajax({
			url : '/member/vendor/load-vendor-tbl',
			type : 'get',
			data : {search_keyword : $(".vendor-search").val(), status : status},
			success : function(data)
			{
				$(".load-vendor").css("opacity", 1);
				$(".vendor-load-data").html(data);
			}
		});
	}
	function initialize_select_plugin()
	{
		$(".select-country").globalDropList(
		{ 
			hasPopup: "false",
			width: "100%",
			onChangeValue: function()
			{
				action_copy_billing_to_shipping($(this));
				//action_check_same_as_bill();
			},
		});
		/*$("body").on("change",".select-country", function()
		{
			action_check_same_as_bill();
		});*/

		$(".select-terms").globalDropList(
		{
			hasPopup: "false",
			width: "100%",
		});
	}
}


