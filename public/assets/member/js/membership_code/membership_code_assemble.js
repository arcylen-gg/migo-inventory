var membership_code_assemble = new membership_code_assemble();
var data_assembly_project_table = {};
var time_out_on_type;

function membership_code_assemble()
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
		event_change_kit_quantiy();
		action_load_item_depending_on_membership_kit();
	}
	function event_change_kit_quantiy()
	{
		$(".membership-item-kit-quantity").keyup(function()
		{
			action_load_item_depending_on_membership_kit();
		});

		$(".membership-item-kit-select").change(function()
		{
			action_load_item_depending_on_membership_kit();
		});
	}
	function action_load_item_depending_on_membership_kit()
	{
		$item_kit_id = $(".membership-item-kit-select").val();
		$item_kit_quantity = $(".membership-item-kit-quantity").val();
		$(".assembly-table-container-projection").html('<div style="padding: 25px; text-align: center;"><i class="fa fa-spinner fa-pulse fa-fw"></i><br>LOADING ITEMS</div>');
		$(".assemble-code-submit").attr("disabled", "disabled");

		clearTimeout(time_out_on_type);
		time_out_on_type = setTimeout(function()
		{
			data_assembly_project_table.item_id = $item_kit_id;
			data_assembly_project_table.quantity = $item_kit_quantity;

			$.ajax(
			{
				url:"/member/mlm/code2/assemble/table",
				data: data_assembly_project_table,
				type:"get",
				success: function(data)
				{
					$(".assembly-table-container-projection").html(data);
					$allowed_assembly = $(".allowed-assembly").val();

					if($allowed_assembly == "false")
					{
						$(".assemble-code-submit").attr("disabled", "disabled");
					}
					else
					{
						$(".assemble-code-submit").removeAttr("disabled");
					}
				}
			});
		}, 500);

	}
}

function membership_code_assemble_success(data)
{
	data.element.modal("hide");
	toastr.success(data.message);
    membership_code.action_load_table();
}