@extends("layout")
@section("content")
<div class="content">
	<div class="top-1-container">
		<div class="container">
			<div class="title-container">Company Events</div>
		</div>
	</div>
	<div class="top-2-container">
		<div class="container">
			<div class="events-container row clearfix">
				<div class="col-md-6">
					<div class="per-event row clearfix">
						<div class="col-md-6">
							<div class="event-image-container">
								<img src="/themes/{{ $shop_theme }}/img/event1.png">
							</div>
						</div>
						<div class="col-md-6">
							<div class="event-details-container">
								<h1>
									Business & Product Presentation	
								</h1>
								<h2>
									July 27, 2017
								</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="per-event row clearfix">
						<div class="col-md-6">
							<div class="event-image-container">
								<img src="/themes/{{ $shop_theme }}/img/event2.png">
							</div>
						</div>
						<div class="col-md-6">
							<div class="event-details-container">
								<h1>
									Quezon City Main Office Opening 
									and Blessing	
								</h1>
								<h2>
									July 27, 2017
								</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="per-event row clearfix">
						<div class="col-md-6">
							<div class="event-image-container">
								<img src="/themes/{{ $shop_theme }}/img/event3.png">
							</div>
						</div>
						<div class="col-md-6">
							<div class="event-details-container">
								<h1>
									General Santos City Branch 
									Opening and Blessing	
								</h1>
								<h2>
									July 27, 2017
								</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="per-event row clearfix">
						<div class="col-md-6">
							<div class="event-image-container">
								<img src="/themes/{{ $shop_theme }}/img/event4.png">
							</div>
						</div>
						<div class="col-md-6">
							<div class="event-details-container">
								<h1>
									General Santos City Branch 
									Opening and Blessing	
								</h1>
								<h2>
									July 27, 2017
								</h2>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>

	<!-- SCROLL TO TOP -->
	<div class="scroll-up"><img src="/themes/{{ $shop_theme }}/img/scroll-up.png"></div>
</div>
@endsection

@section("css")
<link rel="stylesheet" type="text/css" href="/themes/{{ $shop_theme }}/css/company_events.css">
@endsection

@section("js")
<script type="text/javascript">
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
});
</script>
@endsection