var holiday_tag_employee = new holiday_tag_employee();
function holiday_tag_employee()
{
	init();

	function init()
	{
		select_department();
		initialize_select();
	}

	function initialize_select()
	{
		$('.change-filter-department').globalDropList(
		{
			hasPopup : 'false',
            width : "100%",
            onChangeValue : function()
            {
            	filter_department($(this).val());
            }
		});
		$('.change-filter-job-title').globalDropList(
		{
			hasPopup : 'false',
            width : "100%",
            onChangeValue : function()
            {
            	filter_job_title($(this).val());
            }
		});
	}
	function filter_job_title(job_id)
	{

	}
	function filter_department(department_id)
	{
		$.ajax({
			url : '/member/payroll/jobtitlelist/get_job_title_by_department',
			type : 'GET',
			data : {payroll_department_id : department_id},
			success : function(result)
			{
				var html = "<option value='0'>Select Job Title</option>";
				result = JSON.parse(result);
				$(result).each(function(index, data)
				{
					html += "<option value='"+data.payroll_jobtitle_id+"'>"+data.payroll_jobtitle_name+"</option>";
				});
				$(".change-filter-job-title").html(html);				
			}
		});
	}
	function select_department()
	{

	}
}