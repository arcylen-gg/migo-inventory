/* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
function openNav() {
	$("#main").css("width", $("#main").width());
	$("header").css("width", $("header").width());
	$("header").css("margin-left", "250px");
	$(".sticky-nav").addClass("hide");
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
    $(".side-nav-dim").fadeIn(500);
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav() {
	$("#main").css("width", "auto");
	$("header").css("width", "auto");
	$("header").css("margin-left", "0");
	$(".sticky-nav").removeClass("hide");
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
    $(".side-nav-dim").fadeOut(500);
}