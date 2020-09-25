var payroll_employee_approver = new payroll_employee_approver();

function payroll_employee_approver()
{
	init();

	function init()
	{
		document_ready();
	}

	function document_ready()
	{
		$(document).ready(function() 
		{
			
		});
	}
	this.reload = function()
	{
		reload();
	}
	
	function reload()
	{
		window.location.href = "/member/payroll/payroll_admin_dashboard/employee_approver";
	}

}

/* CALL A FUNCTION BY NAME */
function executeFunctionByName(functionName, context /*, args */) 
{
  var args = [].slice.call(arguments).splice(2);
  var namespaces = functionName.split(".");
  var func = namespaces.pop();
  for(var i = 0; i < namespaces.length; i++) {
    context = context[namespaces[i]];
  }
  return context[func].apply(context, args);
}

function submit_done(data)
{
	try
	{
		data = JSON.parse(data);
	}
	catch(err)
	{

	}

	data.element.modal("toggle");

	if(typeof data.type  !== 'undefined')
	{
		
	}
	else
	{
		console.log(data.function_name);
		executeFunctionByName(data.function_name, window);
	}
}