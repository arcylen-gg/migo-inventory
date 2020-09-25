$('body').on('click','.select-action', function()
{
	$('.button-action').val($(this).attr('code'));
	$('.global-submit').submit();
});
$('.new.droplist-terms').globalDropList(
{
    link : "/member/maintenance/terms/terms",
    link_size : "sm",
    width : "100%",
	placeholder : "Terms...",
    onChangeValue: function()
    {
    	var start_date 		= $(".datepicker[name='transaction_date']").val();
    	var days 			= $(this).find("option:selected").attr("days");
    	var new_due_date 	= AddDaysToDate2(start_date, days, "/");
    	$(".datepicker[name='transaction_duedate']").val(new_due_date);
        if(!days)
        {
            $(".datepicker[name='transaction_duedate']").val(start_date);
        }
    }
});
function AddDaysToDate2(sDate, iAddDays, sSeperator) 
{
    //Purpose: Add the specified number of dates to a given date.
    var date = new Date(sDate);
    date.setDate(date.getDate() + parseInt(iAddDays));
    var sEndDate = LPad2(date.getMonth() + 1, 2) + sSeperator + LPad2(date.getDate(), 2) + sSeperator + date.getFullYear();
    return sEndDate;
}
function LPad2(sValue, iPadBy) 
{
    sValue = sValue.toString();
    return sValue.length < iPadBy ? LPad2("0" + sValue, iPadBy) : sValue;
}