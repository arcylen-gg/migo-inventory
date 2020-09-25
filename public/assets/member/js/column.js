var column = new column();

function column()
{
	init();

	this.show_hide_columns= function()
	{
		show_hide_columns();
	};

	function init()
	{
		$(document).ready(function()
		{
			document_ready();
		});
	}
	function document_ready()
	{
		event_click_customize_column();
		event_save_column_settings();
	}
	function event_save_column_settings()
	{
		$("body").on("click", ".save-column-settings", function()
		{
			var table_data = {};

			$(".column-check-list").each(function()
			{
				$column_key = $(this).attr("colkey");

				if($(this).find("input").is(":checked"))
				{
					table_data[$column_key] = "show";
				}
				else
				{
					table_data[$column_key] = "hide";
				}
			});

			//console.log("SAVE COLUMN INFORMATION");
			//console.log(table_data);

			table_data_json = JSON.stringify(table_data);
			$.cookie(table_key, table_data_json);
			$("#custom-modal").modal("hide");
			show_hide_columns();
		});
	}
	function event_click_customize_column()
	{
		$(".customize-column").click(function()
		{
			action_show_custom_column_form();
		});
	}
	function action_show_custom_column_form()
	{
		$("#custom-modal").modal("show");
		$("#custom-modal").find(".modal-title").text("Customize Columns");
		$("#custom-modal").find(".custom-button-submit").text("Save Column");
		$("#custom-modal").find(".custom-button-submit").addClass("save-column-settings");
		$("#custom-modal").find(".modal-body").addClass("clearfix");
		$("#custom-modal").find(".modal-body").html("");

		$table_data = $.cookie(table_key);

		$(".custom-column-table").find("th").each(function(key)
		{
			$column_key = $(this).attr("colkey");

			if(!$table_data)
			{
				if($(this).attr("default") == "hide")
				{
					$checked = "";
				}
				else
				{
					$checked = "checked";
				}
			}
			else
			{
				$table_data_unserialize = JSON.parse($table_data);

				if($table_data_unserialize[$column_key] == "hide")
				{
					$checked = "";
				}
				else
				{
					$checked = "checked";
				}

				//console.log($table_data_unserialize);
			}

			$label = '<div class="column-check-list col-md-6" colkey="' + $(this).attr("colkey")  + '"><label><span style="display: inline-block; vertical-align: top;"><input type="checkbox" ' + $checked + '></span> <span style="display: inline-block; padding: 0 10px;">' + $(this).text() + '</span></label></div>';
			$("#custom-modal").find(".modal-body").append($label);
		});

	}
	function show_hide_columns()
	{
		$table_data = $.cookie(table_key);

		if(!$table_data)
		{
			$(".custom-column-table").find("th").each(function(key)
			{
				$column_key = $(this).attr("colkey");
				
				if($(this).attr("default") == "hide")
				{
					$("th[colkey=" + $column_key + "]").hide();
					$("td[colkey=" + $column_key + "]").hide();
				}
				else
				{
					$("th[colkey=" + $column_key + "]").show();
					$("td[colkey=" + $column_key + "]").show();
				}
			});
		}
		else
		{
			$table_data_unserialize = JSON.parse($table_data);
			$.each($table_data_unserialize, function(key, val)
			{
				//console.log(key + " = " + val);

				if(val == "hide")
				{
					$("th[colkey=" + key + "]").hide();
					$("td[colkey=" + key + "]").hide();
				}
				else
				{
					$("th[colkey=" + key + "]").show();
					$("td[colkey=" + key + "]").show();
				}
			});
		}
	}
}