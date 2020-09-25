var mlm_developer_import = new mlm_developer_import();
var result_import;
var range;

function mlm_developer_import()
{
	init();

	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		});
	}

	function document_ready()
	{
		event_change_import_excel();
		event_click_start_importation();
	}
	function event_click_start_importation()
	{
		$(".start-importation").click(function()
		{
			action_click_start_importation();
		});
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

		import_data["slot_no"] = $target_source.attr("slot_no");
		import_data["sponsor"] = $target_source.attr("sponsor");
		import_data["placement"] = $target_source.attr("placement");
		import_data["position"] = $target_source.attr("position");
		import_data["package_number"] = $target_source.attr("package_number");
		import_data["email"] = $target_source.attr("email");
		import_data["first_name"] = $target_source.attr("first_name");
		import_data["last_name"] = $target_source.attr("last_name");
		import_data["password"] = $target_source.attr("password");
		import_data["date_created"] = $target_source.attr("date_created");
		import_data["_token"] = $(".import-token").val();

		$target_source.find(".status").html("<span><i class='fa fa-spinner fa-pulse fa-fw'></i></span>");

		$.ajax(
		{
			url:"/member/mlm/developer/import",
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
						//$("#global_modal").modal("hide");
						$(".tr-slot-import-data[key=" + pointer + "]").fadeOut('fast');
						mlm_developer.action_load_data();
					}
				}
				else
				{
					$target_source.find(".status").html("<span style='color: red'>" + data.message +"</span>");
					next_pointer = pointer + 1;
					action_import_slot_data(next_pointer);
				}
			}
		});
	}
	function event_change_import_excel()
	{
		$('.import-excel').change(handleFile);
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

    		$append = 		"<tr class='tr-slot-import-data' key='" + key + "' email='" + val["EMAIL"] + "' first_name='" + val["FIRST NAME"] + "' last_name='" + val["LAST NAME"] + "' date_created='" + val["DATE CREATED"] + "' slot_no='" + val["SLOT NO"] + "' sponsor='" + val["SPONSOR"] + "' placement='" + val["PLACEMENT"] + "' position ='" + val["POSITION"] + "' package_number='" + val["PACKAGE NUMBER"] + "' status='pending' password='"+val["PASSWORD"]+"'>" +
    							"<td class='text-center'>" + val["EMAIL"] + "</td>" +
    							"<td class='text-center'>" + val["FIRST NAME"] + "</td>" +
    							"<td class='text-center'>" + val["LAST NAME"] + "</td>" +
    							"<td class='text-center'>" + val["SLOT NO"] + "</td>" +
    							"<td class='text-center'>" + val["DATE CREATED"] + "</td>" +
    							"<td class='text-center'>" + val["SPONSOR"] + "</td>" +
    							"<td class='text-center'>" + val["POSITION"] + " OF " + val["PLACEMENT"] + "</td>" +
    							"<td class='text-center'>" + val["PACKAGE NUMBER"] + "</td>" +
    							"<td class='text-center'>" + val["PASSWORD"] + "</td>" +
    							"<td class='text-center status'><span style='color: green;'>PENDING</span></td>" +
    					+	"</tr>";

    		$(".import-slot-list").append($append);
    		$(".import-button").show();
    		$(".import-excel").hide();

    		range = key;
    	});
    }
}