var modal_shift_import_template = new modal_shift_import_template();
var loader = '<div class="text-center"><div class="loader-16-gray"></div><br><span>Importing data...</span></div>';

function modal_shift_import_template()
{
	init();

	function init()
	{
		file_change_event();
		action_import_file();
	}

	function file_change_event()
	{
		$("#temp-file").unbind("change");
		$("#temp-file").bind("change", function()
		{
			var file_name = $(this)[0].files[0].name;
			$(".file-name").html(file_name);
		});
	} 

	function action_import_file()
	{
		
		$(".btn-import").on("click", function()
		{
			var file = $("#temp-file")[0].files[0];
			if(file != undefined)
			{
				var formdata 	= new FormData();
				var ajax 		= new XMLHttpRequest();

				formdata.append("_token", $("._token").val());
				formdata.append("file",file);
				formdata.append("template", $("#template_name").val())

				ajax.upload.addEventListener("progress",function(event)
				{
					$(".import-status").html(loader);
				},false);

				ajax.addEventListener("load",function(event)
				{
					$(".import-status").html(event.target.responseText);
				},false);

				ajax.open("POST","/member/payroll/shift_template/import_modal_shift_global");
				ajax.send(formdata);
			}
			else
			{
				toastr.error("Please Choose a file first");
			}
		});
	}
}