var payout = new payout();
var payout_table_data = {};

function payout()
{
	init();

	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		});
	}

	this.action_load_table = function()
	{
		action_load_table();
	};

	function document_ready()
	{
		action_load_table();
		add_event_pagination();
		add_event_search();
		event_change_import_excel();
		event_click_start_importation();
		event_reset_payout();
		event_tab_change();
	}
	function event_reset_payout()
	{
		$(".reset-payout").click(function()
		{
			if(prompt("WARNING! All PAYOUT MANUAL PROCESS related to MLM will be deleted. Please write RESET if you are sure.") == "RESET")
			{
				window.location.href = '/member/mlm/payout/reset';
			}
		});
	}
	function event_click_start_importation()
	{
		$("body").on("click", ".start-importation", function()
		{
			action_click_start_importation();
		});
	}
	function event_change_import_excel()
	{
		$('body').on("change", ".import-excel", handleFile);
	}

	function action_click_start_importation()
	{
		action_import_slot_data(0);
	}
	function action_import_slot_data(pointer)
	{
		var import_data = {};

    	$(".import-button").hide();
		$(".show-progress").show();
		var percentage = (pointer / range) * 100;

		$(".show-progress .progress").css("width", percentage + "%");
		console.log(percentage);

		$target_source = $(".tr-slot-import-data[key=" + pointer + "]");

		import_data["payout_date"] = $target_source.attr("payout_date");
		import_data["slot_no"] = $target_source.attr("slot_no");
		import_data["payout_amount"] = $target_source.attr("payout_amount");
		import_data["tax"] = $target_source.attr("tax");
		import_data["service_charge"] = $target_source.attr("service_charge");
		import_data["other_charge"] = $target_source.attr("other_charge");
		import_data["method"] = $target_source.attr("method");
		import_data["_token"] = $(".import-token").val();

		$target_source.find(".status").html("<span><i class='fa fa-spinner fa-pulse fa-fw'></i></span>");

		$.ajax(
		{
			url:"/member/mlm/payout/import",
			dataType:"json",
			data: import_data,
			type: "post",
			success: function(data)
			{
				if(data.status == "success")
				{
					next_pointer = pointer + 1;

					if($(".tr-slot-import-data[key=" + next_pointer + "]").length > 0)
					{
						$(".tr-slot-import-data[key=" + pointer + "]").fadeOut('fast');
						action_import_slot_data(next_pointer);
					}
					else
					{
						$("#global_modal").modal("hide");
						mlm_developer.action_load_data();
					}
				}
				else
				{
					$target_source.find(".status").html("<span style='color: red'>" + data.message +"</span>");
				}
				
			}
		});
	}

	function handleFile(e)
	{
     	//Get the files from Upload control
        var files = e.target.files;
        var i, f;

     	//Loop through files
        for (i = 0, f = files[i]; i != files.length; ++i)
        {
            var reader = new FileReader();
            var name = f.name;
            reader.onload = function (e)
            {
                var data = e.target.result;
                var workbook = XLSX.read(data, { type: 'binary' });
                
                var sheet_name_list = workbook.SheetNames;
                sheet_name_list.forEach(function (y)
                { /* iterate through sheets */
                    //Convert the cell value to Json
                    var roa = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                    if (roa.length > 0)
                    {
                        result_import = roa;
                    }
                });

	        	action_show_import_result_to_table();
        	};

            reader.readAsArrayBuffer(f);
        }
    }
    function action_show_import_result_to_table()
    {
    	$(".import-slot-list").html("");

    	$.each(result_import, function(key, val)
    	{
    		$append = 		"<tr class='tr-slot-import-data' key='" + key + "' payout_date='" + val["PAYOUT DATE"] + "' slot_no='" + val["SLOT NO"] + "' payout_amount='" + val["PAYOUT AMOUNT"] + "' tax='" + val["TAX"] + "' service_charge='" + val["SERVICE CHARGE"] + "' other_charge='" + val["OTHER CHARGE"] + "' total='" + val["TOTAL"] + "' method='" + val["METHOD"] + "' status='pending'>" +
    							"<td class='text-center'>" + val["PAYOUT DATE"] + "</td>" +
    							"<td class='text-center'>" + val["METHOD"] + "</td>" +	
    							"<td class='text-center'>" + val["SLOT NO"] + "</td>" +
    							"<td class='text-center'>" + val["PAYOUT AMOUNT"] + "</td>" +
    							"<td class='text-center'>" + val["TAX"] + "</td>" +
    							"<td class='text-center'>" + val["SERVICE CHARGE"] + "</td>" +
    							"<td class='text-center'>" + val["OTHER CHARGE"] + "</td>" +
    							"<td class='text-center status'><span style='color: green;'>PENDING</span></td>" +
    					+	"</tr>";

    		$(".import-slot-list").append($append);
    		$(".import-button").show();
    		$(".import-excel").hide();

    		range = key;
    	});
    }
	function add_event_search()
	{
		$(".search-employee-name").keypress(function(e)
		{
			if(e.which == 13)
			{
				payout_table_data.page = 1;
				action_load_table();
			}
		});
	}
	function add_event_pagination()
	{
		$("body").on("click", ".pagination a", function(e)
		{
			$url = $(e.currentTarget).attr("href");
			var url = new URL($url);
			$page = url.searchParams.get("page");
			payout_table_data.page = $page;
			action_load_table();
			return false;
		});
	}

    function event_tab_change()
    {
        $(".change-tab").click(function(e)
        {
            $(".change-tab").removeClass("active");
            $(e.currentTarget).addClass("active");
            payout_table_data.page = 1;
            action_load_table();
        });
    }
	function action_load_table()
	{
		payout_table_data._token = $(".payout-token").val();
		payout_table_data.search = $(".search-employee-name").val();
		$active_tab = $(".change-tab.active").attr("mode");
        $(".active-tab").val($active_tab);
        payout_table_data.mode = $active_tab;
		$(".table-container").html('<div class="text-center" style="padding: 50px 10px; font-size: 26px;"><i class="fa fa-spinner fa-pulse fa-fw"></i></div>')

		$.ajax(
		{
			url: "/member/mlm/payout/index-table",
			data: payout_table_data,
			type: "post",
			success: function(data)
			{
				$(".table-container").html(data);
			},
			complete: function()
			{
			}
		})
	}
}
