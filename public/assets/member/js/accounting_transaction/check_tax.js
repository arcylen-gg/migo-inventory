$("body").on("click", ".select-all-tax-check", function()
{
	$(this).toggleClass("unselect-tax");
	if($(this).hasClass("unselect-tax"))
	{
		$(".tax-icon").css('color', '#CCCCCC');
		$all = $(this).parents(".digima-table").find(".taxable-check");
		$all.each(function()
		{
			$(this).prop("checked", false);
		});
		$input = $(this).parents(".digima-table").find(".taxable-input");
		$input.each(function()
		{
			$(this).val(0);
		});
	}
	else
	{
		$(".tax-icon").css('color', 'green');
		$all = $(this).parents(".digima-table").find(".taxable-check");
		$all.each(function()
		{
			$(this).prop("checked", true);
		});

		$input = $(this).parents(".digima-table").find(".taxable-input");
		$input.each(function()
		{
			$(this).val(1);
		});
	}
});
$("body").on("click",".taxable-check", function()
{
	if(!$(this).prop("checked"))
	{
		$(".tax-icon").css('color', '#CCCCCC');
	}
	else
	{
		if($(".digima-table .taxable-check:checked").length == $(".digima-table .taxable-check").length)
		{
			$(".tax-icon").css('color', 'green');
		}
	}
});