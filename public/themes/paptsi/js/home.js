$(document).ready(function()
{
	/*scroll up*/
	$(window).scroll(function () {
        if ($(this).scrollTop() > 700) {
            $('.scroll-up').fadeIn();
        } else {
            $('.scroll-up').fadeOut();
        }
    });

    $('.scroll-up').click(function () {
        $("html, body").animate({
            scrollTop: 0
        }, 700);
        return false;
    });

    /*TEXT FADEOUT*/
    $(window).scroll(function(){
	    	$(".caption-container, .caption-logo-container").css("opacity", 1 - $(window).scrollTop() / 250);
	});

    $(".title-vision").click(function()
    {
        $("#vision").removeClass("hide");
        $("#mission").addClass("hide");
        $(".title-vision").addClass("highlighted");
        $(".title-mission").removeClass("highlighted");
        
    });
    $(".title-mission").click(function()
    {
        $("#vision").addClass("hide");
        $("#mission").removeClass("hide");
        $(".title-mission").addClass("highlighted");
        $(".title-vision").removeClass("highlighted");
    });

});